import React, { useEffect, useMemo, useState, useRef } from 'react';
import { createPortal } from 'react-dom';
import SeatSelection from './SeatSelection';
import SearchForm from './SearchForm';
import {
  buildLocationMap,
  getToday,
  getTomorrow,
  LocationCombobox,
  mapLocationPayload,
  resolveLocationInput,
  VietnameseDatePicker,
  fetchLocationsWithCache,
} from './SearchFields';

const SORT_OPTIONS = [
  { value: 'time:asc', label: 'Giờ đi sớm nhất' },
  { value: 'time:desc', label: 'Giờ đi muộn nhất' },
  { value: 'rating:desc', label: 'Đánh giá cao nhất' },
  { value: 'fare:asc', label: 'Giá tăng dần' },
  { value: 'fare:desc', label: 'Giá giảm dần' },
];



const PRICE_OPTIONS = [
  { value: 'all', label: 'Tất cả giá' },
  { value: 'under-200', label: 'Dưới 200.000đ' },
  { value: '200-400', label: '200.000đ - 400.000đ' },
  { value: 'over-400', label: 'Trên 400.000đ' },
];

const DEFAULT_FILTER_PATCH = {
  companies: '',
  time: '00:00-24:00',
  sort: 'time:asc',
  islimousine: '',
  fa: '',
  ta: '',
  rating: '',
};

const EMPTY_FILTER_OPTIONS = {
  key: '',
  companies: [],
  pickupPoints: [],
  dropoffPoints: [],
};

const getOptionKey = (item, field) => String(item?.[field] ?? '').trim();

const mergeOptionList = (current, incoming, field) => {
  if (!Array.isArray(incoming) || incoming.length === 0) return current;

  const merged = new Map();
  current.forEach((item) => {
    const key = getOptionKey(item, field);
    if (key) merged.set(key, item);
  });

  incoming.forEach((item) => {
    const key = getOptionKey(item, field);
    if (key) {
      merged.set(key, { ...(merged.get(key) || {}), ...item });
    }
  });

  return Array.from(merged.values());
};

const getPointName = (point) => String(point?.district || point?.name || point?.point_name || point?.address || '').trim();

const mergePointList = (current, incoming) => {
  if (!Array.isArray(incoming) || incoming.length === 0) return current;

  const merged = new Map();
  current.forEach((point) => {
    const key = getPointName(point);
    if (key) merged.set(key, point);
  });

  incoming.forEach((point) => {
    const key = getPointName(point);
    if (key) {
      merged.set(key, { ...(merged.get(key) || {}), ...point });
    }
  });

  return Array.from(merged.values());
};

const MobileFilterSheet = ({ title, children, footer, variant = 'compact', onClose }) => {
  const isFull = variant === 'full';

  return (
    <div className="fixed inset-0 z-[1000] lg:hidden" role="dialog" aria-modal="true" aria-label={title}>
      <button
        type="button"
        className="dailyve-mobile-sheet__backdrop absolute inset-0 h-full w-full bg-slate-950/50"
        onClick={onClose}
        aria-label="Đóng"
      ></button>

      <div className={`dailyve-mobile-sheet__panel absolute bottom-0 left-0 right-0 flex flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl ${isFull ? 'h-[88vh]' : 'max-h-[82vh]'}`}>
        {isFull ? (
          <div className="flex h-14 shrink-0 items-center gap-2 bg-blue-600 px-3 text-white">
            <button
              type="button"
              className="flex h-10 w-10 items-center justify-center rounded-full text-white"
              onClick={onClose}
              aria-label="Quay lại"
            >
              <i className="fas fa-arrow-left"></i>
            </button>
            <h2 className="text-base font-bold">{title}</h2>
          </div>
        ) : (
          <div className="shrink-0 bg-white px-4 pt-3">
            <div className="mx-auto mb-2 h-1 w-10 rounded-full bg-slate-300"></div>
            <div className="flex min-h-12 items-center justify-between border-b border-slate-100">
              <h2 className="text-base font-bold text-slate-950">{title}</h2>
              <button type="button" className="text-sm font-bold text-blue-600" onClick={onClose}>
                Đóng
              </button>
            </div>
          </div>
        )}

        <div className={`min-h-0 flex-1 overflow-y-auto ${isFull ? 'bg-slate-50' : 'bg-white'}`}>
          {children}
        </div>

        {footer && (
          <div className="shrink-0 border-t border-slate-100 bg-white px-4 pb-[calc(env(safe-area-inset-bottom)+16px)] pt-3">
            {footer}
          </div>
        )}
      </div>
    </div>
  );
};

const formatDateInput = (value) => {
  if (!value) return '';
  if (/^\d{4}-\d{2}-\d{2}$/.test(value)) return value;

  const match = value.match(/^(\d{2})-(\d{2})-(\d{4})$/);
  if (match) return `${match[3]}-${match[2]}-${match[1]}`;

  return value;
};

const formatCurrency = (value) => {
  const number = Number(value || 0);
  return number.toLocaleString('vi-VN') + 'đ';
};

const formatTime = (value) => {
  const text = String(value || '');
  const timePart = text.includes('T') ? text.split('T')[1] : text;
  return timePart.slice(0, 5);
};

const normalizeImageUrl = (value) => {
  const text = String(value || '').trim();
  if (!text) return '';
  if (/^\/{2,}/.test(text)) return `https://${text.replace(/^\/+/, '')}`;
  return text;
};

const routeDuration = (start, end) => {
  const startDate = new Date(String(start || '').replace(/\+\d{2}:\d{2}$/, ''));
  const endDate = new Date(String(end || '').replace(/\+\d{2}:\d{2}$/, ''));

  if (Number.isNaN(startDate.getTime()) || Number.isNaN(endDate.getTime())) {
    return '';
  }

  const minutes = Math.max(0, Math.round((endDate - startDate) / 60000));
  const hours = Math.floor(minutes / 60);
  const rest = minutes % 60;

  return rest > 0 ? `${hours} giờ ${rest} phút` : `${hours} giờ`;
};

const getInitialFilters = () => {
  const params = new URLSearchParams(window.location.search);

  return {
    from: params.get('from') || '',
    to: params.get('to') || '',
    nameFrom: params.get('nameFrom') || '',
    nameTo: params.get('nameTo') || '',
    date: formatDateInput(params.get('date') || getTomorrow()),
    returnDate: formatDateInput(params.get('returnDate') || ''),
    service: params.get('service') || 'bus',
    sort: params.get('sort') || 'time:asc',
    time: params.get('time') || '00:00-23:59',
    companies: params.get('companies') || '',
    islimousine: params.get('islimousine') || '',
    fa: params.get('fa') || '',
    ta: params.get('ta') || '',
    rating: params.get('rating') || '',
  };
};

const buildQuery = (filters, extras = {}) => {
  const params = new URLSearchParams();
  Object.entries({ ...filters, ...extras }).forEach(([key, value]) => {
    if (value !== undefined && value !== null && value !== '') {
      params.set(key, value);
    }
  });

  return params;
};

const priceMatches = (trip, priceRange) => {
  const fare = Number(trip.fare || 0);
  if (priceRange === 'under-200') return fare < 200000;
  if (priceRange === '200-400') return fare >= 200000 && fare <= 400000;
  if (priceRange === 'over-400') return fare > 400000;
  return true;
};

const TimeRangeSlider = ({ value, onChange }) => {
  const [minStr, maxStr] = (value || "00:00-24:00").split('-');
  const parseTime = (timeStr) => {
    if (!timeStr) return 0;
    const [h, m] = timeStr.split(':');
    return parseInt(h) * 60 + parseInt(m);
  };
  const formatTimeStr = (minutes) => {
    const h = Math.floor(minutes / 60);
    const m = minutes % 60;
    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
  };

  const [minVal, setMinVal] = useState(() => parseTime(minStr));
  const [maxVal, setMaxVal] = useState(() => parseTime(maxStr === '23:59' || maxStr === '24:00' ? '24:00' : maxStr));

  useEffect(() => {
    const [newMin, newMax] = (value || "00:00-24:00").split('-');
    setMinVal(parseTime(newMin));
    setMaxVal(parseTime(newMax === '23:59' || newMax === '24:00' ? '24:00' : newMax));
  }, [value]);

  const handleMinChange = (e) => {
    const newMin = Math.min(Number(e.target.value), maxVal - 60);
    setMinVal(newMin);
  };

  const handleMaxChange = (e) => {
    const newMax = Math.max(Number(e.target.value), minVal + 60);
    setMaxVal(newMax);
  };

  const handleMouseUp = () => {
    onChange(`${formatTimeStr(minVal)}-${maxVal === 1440 ? '24:00' : formatTimeStr(maxVal)}`);
  };

  return (
    <div className="py-2">
      <div className="relative h-1.5 rounded-full bg-slate-200 mb-6 mx-2">
        <div
          className="absolute h-full rounded-full bg-blue-600 pointer-events-none"
          style={{
            left: `${(minVal / 1440) * 100}%`,
            right: `${100 - (maxVal / 1440) * 100}%`
          }}
        ></div>
        <input
          type="range"
          min="0" max="1440" step="30"
          value={minVal}
          onChange={handleMinChange}
          onMouseUp={handleMouseUp}
          onTouchEnd={handleMouseUp}
          className="dailyve-time-slider absolute w-full -top-2.5 h-6 appearance-none bg-transparent pointer-events-none"
          style={{ zIndex: minVal > 1440 - 100 ? 5 : 3 }}
        />
        <input
          type="range"
          min="0" max="1440" step="30"
          value={maxVal}
          onChange={handleMaxChange}
          onMouseUp={handleMouseUp}
          onTouchEnd={handleMouseUp}
          className="dailyve-time-slider absolute w-full -top-2.5 h-6 appearance-none bg-transparent pointer-events-none"
          style={{ zIndex: 4 }}
        />
        <style dangerouslySetInnerHTML={{
          __html: `
          .dailyve-time-slider::-webkit-slider-thumb {
            pointer-events: auto;
            appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #eff6ff;
            border: 2px solid #2563eb;
            cursor: pointer;
          }
          .dailyve-time-slider::-moz-range-thumb {
            pointer-events: auto;
            appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #eff6ff;
            border: 2px solid #2563eb;
            cursor: pointer;
          }
          .dailyve-time-input::-webkit-calendar-picker-indicator {
            display: none;
          }
        `}} />
      </div>
      <div className="flex items-center justify-between gap-3">
        <div className="flex-1 rounded-lg border border-slate-200 p-2 text-center focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all bg-white">
          <div className="text-[10px] text-slate-400 font-semibold mb-0.5">Từ</div>
          <input 
            type="time" 
            className="dailyve-time-input w-full text-center text-sm font-bold text-slate-900 bg-transparent outline-none appearance-none" 
            value={formatTimeStr(minVal)}
            onChange={(e) => {
              const val = parseTime(e.target.value);
              if (val !== null && !isNaN(val)) {
                const newMin = Math.min(val, maxVal - 60);
                setMinVal(newMin);
              }
            }}
            onBlur={() => {
              onChange(`${formatTimeStr(minVal)}-${maxVal === 1440 ? '24:00' : formatTimeStr(maxVal)}`);
            }}
          />
        </div>
        <div className="text-slate-300 font-bold">-</div>
        <div className="flex-1 rounded-lg border border-slate-200 p-2 text-center focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all bg-white">
          <div className="text-[10px] text-slate-400 font-semibold mb-0.5">Đến</div>
          <input 
            type="time" 
            className="dailyve-time-input w-full text-center text-sm font-bold text-slate-900 bg-transparent outline-none appearance-none" 
            value={maxVal === 1440 ? '23:59' : formatTimeStr(maxVal)}
            onChange={(e) => {
              const val = parseTime(e.target.value);
              if (val !== null && !isNaN(val)) {
                const newMax = Math.max(val, minVal + 60);
                setMaxVal(newMax);
              }
            }}
            onBlur={() => {
              onChange(`${formatTimeStr(minVal)}-${maxVal === 1440 ? '24:00' : formatTimeStr(maxVal)}`);
            }}
          />
        </div>
      </div>
    </div>
  );
};

const SearchPanel = ({ filters, onSubmit }) => {
  const [locations, setLocations] = useState([]);
  const [from, setFrom] = useState(filters.from);
  const [to, setTo] = useState(filters.to);
  const [fromQuery, setFromQuery] = useState(filters.nameFrom || '');
  const [toQuery, setToQuery] = useState(filters.nameTo || '');
  const [date, setDate] = useState(filters.date);
  const [returnDate, setReturnDate] = useState(filters.returnDate || '');
  const [isRoundTrip, setIsRoundTrip] = useState(!!filters.returnDate);

  useEffect(() => {
    setFrom(filters.from);
    setTo(filters.to);
    setFromQuery(filters.nameFrom || '');
    setToQuery(filters.nameTo || '');
    setDate(filters.date);
    setReturnDate(filters.returnDate || '');
    setIsRoundTrip(!!filters.returnDate);
  }, [filters]);

  useEffect(() => {
    fetchLocationsWithCache(setLocations);
  }, []);

  const locationMap = useMemo(() => buildLocationMap(locations), [locations]);

  useEffect(() => {
    if (from && locationMap[from]) {
      setFromQuery(locationMap[from].name);
    }
  }, [from, locationMap]);

  useEffect(() => {
    if (to && locationMap[to]) {
      setToQuery(locationMap[to].name);
    }
  }, [to, locationMap]);

  const handleDepartureDateChange = (nextDate) => {
    setDate(nextDate);

    if (returnDate && nextDate && returnDate < nextDate) {
      setReturnDate('');
    }
  };

  return (
    <div className="dailyve-search dailyve-search--results">
      <div className="dailyve-search__tabs dailyve-search__tabs--route" role="tablist" aria-label="Chọn loại hành trình">
        <button
          type="button"
          role="tab"
          aria-selected={!isRoundTrip}
          onClick={() => {
            setIsRoundTrip(false);
            setReturnDate('');
          }}
          className={!isRoundTrip ? 'is-active' : ''}
        >
          Một chiều
        </button>
        <button
          type="button"
          role="tab"
          aria-selected={isRoundTrip}
          onClick={() => setIsRoundTrip(true)}
          className={isRoundTrip ? 'is-active' : ''}
        >
          Khứ hồi
        </button>
      </div>

      <form
        className="dailyve-search__form"
        onSubmit={(event) => {
          event.preventDefault();
          const fromLocation = resolveLocationInput(locations, locationMap, from, fromQuery);
          const toLocation = resolveLocationInput(locations, locationMap, to, toQuery);

          if (!fromLocation || !toLocation) {
            alert('Vui lòng chọn điểm đi và điểm đến trong danh sách.');
            return;
          }

          if (isRoundTrip && !returnDate) {
            alert('Vui lòng chọn ngày về cho chuyến khứ hồi');
            return;
          }

          onSubmit({
            from: fromLocation.id,
            to: toLocation.id,
            date,
            returnDate: isRoundTrip ? returnDate : '',
            nameFrom: fromLocation.name,
            nameTo: toLocation.name,
          });
        }}
      >
        <div className="dailyve-search__locations-wrapper">
          <LocationCombobox
            fieldId="dailyve-result-from"
            label="Nơi đi"
            icon="fas fa-map-marker-alt"
            placeholder="Nhập nơi đi"
            locations={locations}
            value={from}
            inputValue={fromQuery}
            onValueChange={setFrom}
            onInputValueChange={setFromQuery}
          />

          <button
            type="button"
            className="dailyve-search__swap"
            onClick={() => {
              const temp = from;
              const tempQuery = fromQuery;
              setFrom(to);
              setFromQuery(toQuery);
              setTo(temp);
              setToQuery(tempQuery);
            }}
            aria-label="Đổi chiều"
          >
            <i className="fas fa-exchange-alt"></i>
          </button>

          <LocationCombobox
            fieldId="dailyve-result-to"
            label="Nơi đến"
            icon="fas fa-map-pin"
            placeholder="Nhập nơi đến"
            locations={locations}
            value={to}
            inputValue={toQuery}
            onValueChange={setTo}
            onInputValueChange={setToQuery}
          />
        </div>

        <VietnameseDatePicker
          label="Ngày đi"
          icon="fas fa-calendar-day"
          value={date}
          min={getToday()}
          onChange={handleDepartureDateChange}
          className="dailyve-search__field dailyve-search__field--date"
          required
        />

        <VietnameseDatePicker
          label="Ngày về"
          icon="fas fa-calendar-check"
          value={returnDate}
          min={date || getToday()}
          onChange={(nextDate) => {
            setReturnDate(nextDate);
            if (nextDate) {
              setIsRoundTrip(true);
            }
          }}
          emptyText={isRoundTrip ? 'Chọn ngày về' : 'Một chiều'}
          clearable
          className="dailyve-search__field dailyve-search__field--return"
        />

        <button
          type="submit"
          className="dailyve-search__submit"
        >
          <i className="fas fa-search" aria-hidden="true"></i>
          Tìm chuyến
        </button>
      </form>
    </div>
  );
};

const TripSkeleton = () => (
  <div className="dailyve-trip-skeleton grid animate-pulse gap-5 overflow-hidden rounded-[18px] border border-slate-100 bg-white p-4 shadow-sm sm:p-5 lg:grid-cols-[140px_minmax(0,1fr)_200px] lg:items-center">
    <div className="aspect-[4/3] w-full max-w-[150px] rounded-xl bg-slate-100"></div>
    <div className="min-w-0 flex-1 space-y-4 py-2">
      <div className="h-6 w-1/3 rounded-md bg-slate-100"></div>
      <div className="flex gap-2">
        <div className="h-4 w-20 rounded bg-slate-100"></div>
        <div className="h-4 w-20 rounded bg-slate-100"></div>
      </div>
      <div className="h-10 w-full rounded-md bg-slate-50"></div>
    </div>
    <div className="w-full space-y-3 border-t border-slate-100 pt-4 lg:border-l lg:border-t-0 lg:pl-6 lg:pt-0">
      <div className="ml-auto h-8 w-24 rounded bg-slate-100"></div>
      <div className="h-12 w-full rounded-xl bg-slate-100"></div>
    </div>
  </div>
);

const FilterPanel = ({ filters, statistics, priceRange, onPriceRange, onChange, resultCount = 0, cacheKey = '' }) => {
  const incomingCompanies = useMemo(
    () => (statistics?.companies?.data || []).filter((company) => Number(company.id) !== 11071),
    [statistics]
  );
  const incomingPickupPoints = useMemo(() => statistics?.pickup_points || [], [statistics]);
  const incomingDropoffPoints = useMemo(() => statistics?.dropoff_points || [], [statistics]);
  const vehicleTypes = Array.isArray(statistics?.vehicle_types) ? statistics.vehicle_types : [];
  const selectedCompanies = filters.companies ? filters.companies.split(',').filter(Boolean) : [];
  const selectedPickups = filters.fa ? filters.fa.split(',').filter(Boolean) : [];
  const selectedDropoffs = filters.ta ? filters.ta.split(',').filter(Boolean) : [];
  const [activeSheet, setActiveSheet] = useState(null);
  const [companyQuery, setCompanyQuery] = useState('');
  const [pointQuery, setPointQuery] = useState('');
  const [optionCache, setOptionCache] = useState(() => ({ ...EMPTY_FILTER_OPTIONS, key: cacheKey }));

  const toggleCompany = (companyId) => {
    const next = new Set(selectedCompanies);
    if (next.has(String(companyId))) {
      next.delete(String(companyId));
    } else {
      next.add(String(companyId));
    }

    onChange({ companies: Array.from(next).join(',') });
  };

  const [companiesExpanded, setCompaniesExpanded] = useState(() => selectedCompanies.length > 0);
  const [pickupsExpanded, setPickupsExpanded] = useState(() => !!filters.fa);
  const [dropoffsExpanded, setDropoffsExpanded] = useState(() => !!filters.ta);

  const selectedPriceLabel = PRICE_OPTIONS.find((option) => option.value === priceRange)?.label || 'Tất cả giá';
  const activeOptionCache = optionCache.key === cacheKey ? optionCache : EMPTY_FILTER_OPTIONS;
  const cachedCompanyList = mergeOptionList(activeOptionCache.companies, incomingCompanies, 'id');
  const cachedPickupPoints = mergePointList(activeOptionCache.pickupPoints, incomingPickupPoints);
  const cachedDropoffPoints = mergePointList(activeOptionCache.dropoffPoints, incomingDropoffPoints);
  const companyList = [
    ...selectedCompanies
      .filter((companyId) => !cachedCompanyList.some((company) => String(company.id) === companyId))
      .map((companyId) => ({ id: companyId, name: `Nhà xe đã chọn (${companyId})`, trip_count: 0 })),
    ...cachedCompanyList,
  ];
  const pickupPoints = [
    ...selectedPickups
      .filter((pointName) => !cachedPickupPoints.some((point) => getPointName(point) === pointName))
      .map((pointName) => ({ district: pointName, trip_count: 0 })),
    ...cachedPickupPoints,
  ];
  const dropoffPoints = [
    ...selectedDropoffs
      .filter((pointName) => !cachedDropoffPoints.some((point) => getPointName(point) === pointName))
      .map((pointName) => ({ district: pointName, trip_count: 0 })),
    ...cachedDropoffPoints,
  ];
  const normalizedCompanyQuery = companyQuery.trim().toLowerCase();
  const normalizedPointQuery = pointQuery.trim().toLowerCase();
  const visibleCompanies = companyList.filter((company) => (
    !normalizedCompanyQuery || String(company.name || '').toLowerCase().includes(normalizedCompanyQuery)
  ));
  const visiblePickupPoints = pickupPoints.filter((point) => (
    !normalizedPointQuery || getPointName(point).toLowerCase().includes(normalizedPointQuery)
  ));
  const visibleDropoffPoints = dropoffPoints.filter((point) => (
    !normalizedPointQuery || getPointName(point).toLowerCase().includes(normalizedPointQuery)
  ));
  const resultButtonLabel = Number.isFinite(resultCount) ? `Xem ${resultCount} chuyến` : 'Xem chuyến';
  const activeFilterCount = [
    filters.time && filters.time !== '00:00-24:00' && filters.time !== '00:00-23:59',
    priceRange !== 'all',
    selectedCompanies.length > 0,
    selectedPickups.length > 0,
    selectedDropoffs.length > 0,
    !!filters.rating,
    !!filters.islimousine,
  ].filter(Boolean).length;

  const resetAllFilters = () => {
    onPriceRange('all');
    onChange(DEFAULT_FILTER_PATCH);
  };

  const closeSheet = () => {
    setActiveSheet(null);
    setCompanyQuery('');
    setPointQuery('');
  };

  const openSheet = (sheet) => {
    setCompanyQuery('');
    setPointQuery('');
    setActiveSheet(sheet);
  };

  const toggleFilterValue = (field, value) => {
    if (!value) return;

    const current = filters[field] ? filters[field].split(',').filter(Boolean) : [];
    const next = current.includes(value) ? current.filter((item) => item !== value) : [...current, value];
    onChange({ [field]: next.join(',') });
  };

  const clearFilterField = (field) => {
    onChange({ [field]: '' });
  };

  useEffect(() => {
    setOptionCache((current) => (
      current.key === cacheKey ? current : { ...EMPTY_FILTER_OPTIONS, key: cacheKey }
    ));
  }, [cacheKey]);

  useEffect(() => {
    if (incomingCompanies.length === 0 && incomingPickupPoints.length === 0 && incomingDropoffPoints.length === 0) {
      return;
    }

    setOptionCache((current) => {
      const base = current.key === cacheKey ? current : { ...EMPTY_FILTER_OPTIONS, key: cacheKey };
      const next = {
        key: cacheKey,
        companies: mergeOptionList(base.companies, incomingCompanies, 'id'),
        pickupPoints: mergePointList(base.pickupPoints, incomingPickupPoints),
        dropoffPoints: mergePointList(base.dropoffPoints, incomingDropoffPoints),
      };

      if (
        next.companies === base.companies &&
        next.pickupPoints === base.pickupPoints &&
        next.dropoffPoints === base.dropoffPoints
      ) {
        return base;
      }

      return next;
    });
  }, [cacheKey, incomingCompanies, incomingPickupPoints, incomingDropoffPoints]);

  useEffect(() => {
    if (!activeSheet || typeof document === 'undefined') return undefined;

    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';

    return () => {
      document.body.style.overflow = previousOverflow;
    };
  }, [activeSheet]);

  const renderRadioSheet = (title, options, value, handleSelect) => (
    <MobileFilterSheet title={title} onClose={closeSheet}>
      <div className="px-4 py-2">
        {options.map((option) => {
          const checked = option.value === value;

          return (
            <button
              key={option.value}
              type="button"
              className="flex min-h-14 w-full items-center justify-between border-b border-slate-100 text-left text-sm text-slate-900"
              onClick={() => {
                handleSelect(option.value);
                closeSheet();
              }}
            >
              <span>{option.label}</span>
              <span className={`flex h-5 w-5 items-center justify-center rounded-full border-2 ${checked ? 'border-blue-600' : 'border-slate-800'}`}>
                {checked && <span className="h-2.5 w-2.5 rounded-full bg-blue-600"></span>}
              </span>
            </button>
          );
        })}
      </div>
    </MobileFilterSheet>
  );

  const renderFilterRow = (label, value, sheet, disabled = false) => (
    <button
      type="button"
      className="flex min-h-12 w-full items-center justify-between border-b border-slate-100 bg-white px-5 py-4 text-left disabled:opacity-50"
      onClick={() => openSheet(sheet)}
      disabled={disabled}
    >
      <span className="text-sm font-semibold text-slate-950">{label}</span>
      <span className="flex min-w-0 items-center gap-3 text-sm text-slate-400">
        <span className="max-w-[180px] truncate">{value}</span>
        <i className="fas fa-chevron-right text-xs"></i>
      </span>
    </button>
  );

  const renderFilterSheet = () => (
    <MobileFilterSheet
      title="Lọc"
      variant="full"
      onClose={closeSheet}
      footer={(
        <div className="grid grid-cols-2 gap-3">
          <button
            type="button"
            className="min-h-12 rounded-lg border border-slate-200 bg-white text-sm font-bold text-slate-950"
            onClick={resetAllFilters}
          >
            Xóa lọc
          </button>
          <button
            type="button"
            className="min-h-12 rounded-lg bg-slate-900 text-sm font-bold text-white"
            onClick={closeSheet}
          >
            {resultButtonLabel}
          </button>
        </div>
      )}
    >
      <div className="space-y-2">
        <section className="bg-white px-5 py-4">
          <div className="mb-3 flex items-center justify-between">
            <h3 className="text-sm font-semibold text-slate-950">Giờ đi</h3>
          </div>
          <TimeRangeSlider value={filters.time} onChange={(val) => onChange({ time: val })} />
        </section>

        <div className="bg-white">
          {renderFilterRow('Nhà xe', selectedCompanies.length ? `${selectedCompanies.length} đã chọn` : 'Tất cả', 'company', companyList.length === 0)}
          {renderFilterRow('Điểm đón', selectedPickups.length ? `${selectedPickups.length} đã chọn` : 'Tất cả', 'pickup', pickupPoints.length === 0)}
          {renderFilterRow('Điểm trả', selectedDropoffs.length ? `${selectedDropoffs.length} đã chọn` : 'Tất cả', 'dropoff', dropoffPoints.length === 0)}
          {renderFilterRow('Loại xe', filters.islimousine ? 'Limousine' : 'Tất cả', 'vehicle')}
        </div>

        <section className="bg-white px-5 py-4">
          <div className="mb-3 flex items-center justify-between">
            <h3 className="text-sm font-semibold text-slate-950">Giá vé</h3>
            <span className="text-sm font-bold text-slate-950">{selectedPriceLabel}</span>
          </div>
          <div className="grid grid-cols-2 gap-2">
            {PRICE_OPTIONS.map((option) => (
              <button
                key={option.value}
                type="button"
                className={`min-h-11 rounded-lg border px-3 text-sm ${priceRange === option.value
                  ? 'border-blue-600 bg-blue-50 text-blue-700'
                  : 'border-slate-200 bg-white text-slate-700'
                  }`}
                onClick={() => onPriceRange(option.value)}
              >
                {option.label}
              </button>
            ))}
          </div>
        </section>

        <section className="bg-white px-5 py-4">
          <h3 className="mb-3 text-xs font-bold uppercase text-slate-400">Tiêu chí phổ biến</h3>
          <div className="space-y-3">
            {[4, 3].map((r) => (
              <button
                key={r}
                type="button"
                className={`flex min-h-12 w-full items-center justify-between rounded-lg border px-4 text-left text-sm ${filters.rating === `${r}-5`
                  ? 'border-blue-600 bg-blue-50 text-blue-700'
                  : 'border-slate-200 bg-white text-slate-700'
                  }`}
                onClick={() => onChange({ rating: filters.rating === `${r}-5` ? '' : `${r}-5` })}
              >
                <span>Từ {r} sao</span>
                <span className="text-yellow-400">
                  {Array.from({ length: r }).map((_, index) => (
                    <i key={index} className="fas fa-star text-xs"></i>
                  ))}
                </span>
              </button>
            ))}
          </div>
        </section>
      </div>
    </MobileFilterSheet>
  );

  const renderVehicleSheet = () => (
    <MobileFilterSheet
      title="Loại xe"
      onClose={closeSheet}
      footer={(
        <div className="grid grid-cols-2 gap-3">
          <button type="button" className="min-h-12 rounded-lg border border-slate-200 bg-white text-sm font-bold text-slate-950" onClick={() => onChange({ islimousine: '' })}>
            Bỏ chọn
          </button>
          <button type="button" className="min-h-12 rounded-lg bg-slate-900 text-sm font-bold text-white" onClick={closeSheet}>
            Lưu
          </button>
        </div>
      )}
    >
      <div className="px-4 py-2">
        <button
          type="button"
          className="flex min-h-14 w-full items-center justify-between border-b border-slate-100 text-left text-sm text-slate-900"
          onClick={() => onChange({ islimousine: filters.islimousine ? '' : '1' })}
        >
          <span>Limousine</span>
          <span className={`flex h-5 w-5 items-center justify-center rounded border ${filters.islimousine ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-400 bg-white'}`}>
            {filters.islimousine && <i className="fas fa-check text-[10px]"></i>}
          </span>
        </button>
        {vehicleTypes.length > 0 && (
          <p className="mt-4 text-xs leading-relaxed text-slate-400">
            Các loại xe khác sẽ được Dailyve cập nhật thêm khi API trả về mã lọc tương ứng.
          </p>
        )}
      </div>
    </MobileFilterSheet>
  );

  const renderCompanySheet = () => (
    <MobileFilterSheet
      title="Chọn nhà xe"
      variant="full"
      onClose={closeSheet}
      footer={(
        <div className="grid grid-cols-2 gap-3">
          <button type="button" className="min-h-12 rounded-lg border border-slate-200 bg-white text-sm font-bold text-slate-950" onClick={() => clearFilterField('companies')}>
            Bỏ chọn tất cả
          </button>
          <button type="button" className="min-h-12 rounded-lg bg-slate-900 text-sm font-bold text-white" onClick={closeSheet}>
            Lưu
          </button>
        </div>
      )}
    >
      <div className="bg-white px-4 py-3">
        <label className="flex h-11 items-center gap-3 rounded-full bg-slate-50 px-4 text-slate-400">
          <i className="fas fa-search"></i>
          <input
            type="search"
            value={companyQuery}
            onChange={(event) => setCompanyQuery(event.target.value)}
            className="h-full min-w-0 flex-1 border-0 bg-transparent text-sm outline-none"
            placeholder="Tìm trong danh sách"
          />
        </label>
      </div>

      <div className="bg-white px-4">
        {visibleCompanies.map((company) => {
          const checked = selectedCompanies.includes(String(company.id));
          const rating = company.rating || company.average_rating || company.avg_rating;

          return (
            <label key={company.id} className="flex min-h-14 cursor-pointer items-center gap-3 border-b border-slate-100 py-2">
              <input
                type="checkbox"
                className="h-4 w-4 rounded border-slate-400 text-blue-600"
                checked={checked}
                onChange={() => toggleCompany(company.id)}
              />
              <span className="min-w-0 flex-1 truncate text-sm font-semibold text-slate-950">{company.name}</span>
              <span className="shrink-0 text-xs text-slate-500">
                {rating ? (
                  <>{Number(rating).toFixed(1)} <i className="fas fa-star text-yellow-400"></i></>
                ) : (
                  `${company.trip_count || 0} chuyến`
                )}
              </span>
            </label>
          );
        })}
        {visibleCompanies.length === 0 && (
          <div className="py-8 text-center text-sm font-semibold text-slate-400">Không tìm thấy nhà xe phù hợp.</div>
        )}
      </div>
    </MobileFilterSheet>
  );

  const renderPointSheet = (type) => {
    const isPickup = type === 'pickup';
    const field = isPickup ? 'fa' : 'ta';
    const points = isPickup ? visiblePickupPoints : visibleDropoffPoints;
    const selected = isPickup ? selectedPickups : selectedDropoffs;

    return (
      <MobileFilterSheet
        title={isPickup ? 'Chọn điểm đón' : 'Chọn điểm trả'}
        variant="full"
        onClose={closeSheet}
        footer={(
          <div className="grid grid-cols-2 gap-3">
            <button type="button" className="min-h-12 rounded-lg border border-slate-200 bg-white text-sm font-bold text-slate-950" onClick={() => clearFilterField(field)}>
              Bỏ chọn tất cả
            </button>
            <button type="button" className="min-h-12 rounded-lg bg-slate-950 text-sm font-bold text-white" onClick={closeSheet}>
              Lưu
            </button>
          </div>
        )}
      >
        <div className="bg-white px-4 py-3">
          <label className="flex h-11 items-center gap-3 rounded-full bg-slate-50 px-4 text-slate-400">
            <i className="fas fa-search"></i>
            <input
              type="search"
              value={pointQuery}
              onChange={(event) => setPointQuery(event.target.value)}
              className="h-full min-w-0 flex-1 border-0 bg-transparent text-sm outline-none"
              placeholder="Tìm trong danh sách"
            />
          </label>
        </div>

        <div className="bg-white px-4">
          {points.map((point, index) => {
            const pointName = getPointName(point);
            const checked = selected.includes(pointName);

            return (
              <label key={`${pointName}-${index}`} className="flex min-h-14 cursor-pointer items-center gap-3 border-b border-slate-100 py-2">
                <input
                  type="checkbox"
                  className="h-4 w-4 rounded border-slate-400 text-blue-600"
                  checked={checked}
                  onChange={() => toggleFilterValue(field, pointName)}
                />
                <span className="min-w-0 flex-1 truncate text-sm font-semibold text-slate-950">{pointName}</span>
                <span className="shrink-0 text-xs text-slate-400">{point.trip_count || 0} chuyến</span>
              </label>
            );
          })}
          {points.length === 0 && (
            <div className="py-8 text-center text-sm font-semibold text-slate-400">Không tìm thấy điểm phù hợp.</div>
          )}
        </div>
      </MobileFilterSheet>
    );
  };

  const renderActiveSheet = () => {
    if (activeSheet === 'sort') {
      return renderRadioSheet('Sắp xếp', SORT_OPTIONS, filters.sort, (value) => onChange({ sort: value }));
    }

    if (activeSheet === 'time') {
      return (
        <MobileFilterSheet title="Giờ đi" onClose={closeSheet}>
          <div className="px-5 py-4">
            <TimeRangeSlider value={filters.time} onChange={(val) => onChange({ time: val })} />
          </div>
        </MobileFilterSheet>
      );
    }

    if (activeSheet === 'filter') return renderFilterSheet();
    if (activeSheet === 'company') return renderCompanySheet();
    if (activeSheet === 'pickup') return renderPointSheet('pickup');
    if (activeSheet === 'dropoff') return renderPointSheet('dropoff');
    if (activeSheet === 'vehicle') return renderVehicleSheet();

    return null;
  };

  const mobileSheet = activeSheet && typeof document !== 'undefined'
    ? createPortal(renderActiveSheet(), document.body)
    : null;

  const MobileToolbarButton = ({ sheet, icon, label, badge, active }) => (
    <button
      type="button"
      className={`relative flex h-10 items-center justify-center gap-1.5 rounded-full px-3 sm:px-4 text-[11px] sm:text-xs font-bold transition-all duration-200 cursor-pointer active:scale-95 ${active
        ? 'bg-[#2196F3] text-white shadow-md shadow-[#2196F3]/20'
        : 'text-slate-300 hover:text-white hover:bg-white/5'
        }`}
      onClick={() => openSheet(sheet)}
    >
      <i className={`fas ${icon} text-[10px] sm:text-[11px] ${active ? 'text-white' : 'text-slate-400'}`}></i>
      <span>{label}</span>
      {badge ? (
        <span className="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] leading-none text-white">
          {badge}
        </span>
      ) : null}
    </button>
  );

  return (
    <>
      <aside className="dailyve-filter-panel order-2 hidden gap-4 lg:order-1 lg:sticky lg:top-24 lg:grid lg:self-start">
        <div className="dailyve-filter-card overflow-hidden rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
          <div className="mb-6 flex items-center justify-between border-b border-slate-50 pb-4">
            <h2 className="text-lg font-black text-slate-900">Bộ lọc</h2>
            <button
              type="button"
              className="text-sm font-bold text-blue-600 transition-colors hover:text-blue-700"
              onClick={resetAllFilters}
            >
              Xóa hết
            </button>
          </div>

          <div className="space-y-6">
            <section className="space-y-3">
              <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Sắp xếp</h3>
              <div className="relative">
                <select
                  className="w-full h-11 pl-4 pr-10 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 cursor-pointer appearance-none shadow-sm"
                  value={filters.sort}
                  onChange={(event) => onChange({ sort: event.target.value })}
                >
                  {SORT_OPTIONS.map((option) => (
                    <option key={option.value} value={option.value}>
                      {option.label}
                    </option>
                  ))}
                </select>
                <i className="fas fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
              </div>
            </section>

            <section className="space-y-3">
              <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Giờ đi</h3>
              <TimeRangeSlider value={filters.time} onChange={(val) => onChange({ time: val })} />
            </section>

            <section className="space-y-3">
              <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Mức giá</h3>
              <div className="relative">
                <select
                  className="w-full h-11 pl-4 pr-10 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 cursor-pointer appearance-none shadow-sm"
                  value={priceRange}
                  onChange={(event) => onPriceRange(event.target.value)}
                >
                  {PRICE_OPTIONS.map((option) => (
                    <option key={option.value} value={option.value}>
                      {option.label}
                    </option>
                  ))}
                </select>
                <i className="fas fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
              </div>
            </section>

            <section className="space-y-3">
              <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Đánh giá</h3>
              <div className="grid grid-cols-1 gap-2">
                {[4, 3, 2].map((r) => (
                  <button
                    key={r}
                    type="button"
                    onClick={() => onChange({ rating: filters.rating === `${r}-5` ? '' : `${r}-5` })}
                    className={`flex items-center justify-between rounded-xl border-2 px-4 py-2.5 text-sm font-bold transition-all ${filters.rating === `${r}-5`
                      ? 'border-blue-600 bg-blue-600 text-white shadow-lg shadow-blue-200'
                      : 'border-slate-50 bg-slate-50 text-slate-600 hover:border-slate-200'
                      }`}
                  >
                    <span className="flex items-center gap-1">
                      {Array.from({ length: r }).map((_, i) => (
                        <i key={i} className="fas fa-star text-yellow-400 text-[10px]"></i>
                      ))}
                      <span className="ml-1">từ {r} sao</span>
                    </span>
                  </button>
                ))}
              </div>
            </section>

            {companyList.length > 0 && (
              <section className="space-y-3 border-t border-slate-100 pt-4">
                <button
                  type="button"
                  onClick={() => setCompaniesExpanded(!companiesExpanded)}
                  className="flex w-full items-center justify-between text-left focus:outline-none group/btn cursor-pointer"
                >
                  <h3 className="text-xs font-black uppercase tracking-widest text-slate-400 group-hover/btn:text-slate-600 transition-colors">Nhà xe</h3>
                  <span className="text-xs text-slate-400 group-hover/btn:text-slate-600 transition-colors flex items-center">
                    {selectedCompanies.length > 0 && (
                      <span className="mr-2 rounded-full bg-blue-50 px-2 py-0.5 text-[9px] font-black text-blue-600">
                        {selectedCompanies.length}
                      </span>
                    )}
                    <i className={`fas fa-chevron-down transition-transform duration-200 ${companiesExpanded ? 'rotate-180' : ''}`}></i>
                  </span>
                </button>

                {companiesExpanded && (
                  <div className="max-h-60 space-y-1 overflow-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 animate-in fade-in slide-in-from-top-2 duration-200">
                    {companyList
                      .map((company) => (
                        <label
                          key={company.id}
                          className={`group flex cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2.5 transition-all ${selectedCompanies.includes(String(company.id))
                            ? 'bg-blue-50 text-blue-700'
                            : 'hover:bg-slate-50 text-slate-600'
                            }`}
                        >
                          <span className="flex min-w-0 items-center gap-3">
                            <input
                              type="checkbox"
                              className="h-4 w-4 rounded-md border-slate-300 text-blue-600 transition-all focus:ring-blue-500"
                              checked={selectedCompanies.includes(String(company.id))}
                              onChange={() => toggleCompany(company.id)}
                            />
                            <span className="truncate text-sm font-bold">{company.name}</span>
                          </span>
                          <span className="text-[10px] font-black opacity-40">{company.trip_count}</span>
                        </label>
                      ))}
                  </div>
                )}
              </section>
            )}

            {pickupPoints.length > 0 && (
              <section className="space-y-3 border-t border-slate-100 pt-4">
                <button
                  type="button"
                  onClick={() => setPickupsExpanded(!pickupsExpanded)}
                  className="flex w-full items-center justify-between text-left focus:outline-none group/btn cursor-pointer"
                >
                  <h3 className="text-xs font-black uppercase tracking-widest text-slate-400 group-hover/btn:text-slate-600 transition-colors">Điểm đón</h3>
                  <span className="text-xs text-slate-400 group-hover/btn:text-slate-600 transition-colors flex items-center">
                    {filters.fa && (
                      <span className="mr-2 rounded-full bg-blue-50 px-2 py-0.5 text-[9px] font-black text-blue-600">
                        {filters.fa.split(',').filter(Boolean).length}
                      </span>
                    )}
                    <i className={`fas fa-chevron-down transition-transform duration-200 ${pickupsExpanded ? 'rotate-180' : ''}`}></i>
                  </span>
                </button>

                {pickupsExpanded && (
                  <div className="max-h-60 space-y-1 overflow-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 animate-in fade-in slide-in-from-top-2 duration-200">
                    {pickupPoints.map((point, idx) => {
                      const pointName = getPointName(point);
                      const selected = filters.fa.split(',').includes(pointName);
                      return (
                        <label
                          key={idx}
                          className={`group flex cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2.5 transition-all ${selected ? 'bg-blue-50 text-blue-700' : 'hover:bg-slate-50 text-slate-600'
                            }`}
                        >
                          <span className="flex min-w-0 items-center gap-3">
                            <input
                              type="checkbox"
                              className="h-4 w-4 rounded-md border-slate-300 text-blue-600"
                              checked={selected}
                              onChange={() => {
                                const current = filters.fa ? filters.fa.split(',') : [];
                                const next = selected ? current.filter(x => x !== pointName) : [...current, pointName];
                                onChange({ fa: next.join(',') });
                              }}
                            />
                            <span className="truncate text-sm font-bold">{pointName}</span>
                          </span>
                          <span className="text-[10px] font-black opacity-40">{point.trip_count}</span>
                        </label>
                      );
                    })}
                  </div>
                )}
              </section>
            )}

            {dropoffPoints.length > 0 && (
              <section className="space-y-3 border-t border-slate-100 pt-4">
                <button
                  type="button"
                  onClick={() => setDropoffsExpanded(!dropoffsExpanded)}
                  className="flex w-full items-center justify-between text-left focus:outline-none group/btn cursor-pointer"
                >
                  <h3 className="text-xs font-black uppercase tracking-widest text-slate-400 group-hover/btn:text-slate-600 transition-colors">Điểm trả</h3>
                  <span className="text-xs text-slate-400 group-hover/btn:text-slate-600 transition-colors flex items-center">
                    {filters.ta && (
                      <span className="mr-2 rounded-full bg-blue-50 px-2 py-0.5 text-[9px] font-black text-blue-600">
                        {filters.ta.split(',').filter(Boolean).length}
                      </span>
                    )}
                    <i className={`fas fa-chevron-down transition-transform duration-200 ${dropoffsExpanded ? 'rotate-180' : ''}`}></i>
                  </span>
                </button>

                {dropoffsExpanded && (
                  <div className="max-h-60 space-y-1 overflow-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 animate-in fade-in slide-in-from-top-2 duration-200">
                    {dropoffPoints.map((point, idx) => {
                      const pointName = getPointName(point);
                      const selected = filters.ta.split(',').includes(pointName);
                      return (
                        <label
                          key={idx}
                          className={`group flex cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2.5 transition-all ${selected ? 'bg-blue-50 text-blue-700' : 'hover:bg-slate-50 text-slate-600'
                            }`}
                        >
                          <span className="flex min-w-0 items-center gap-3">
                            <input
                              type="checkbox"
                              className="h-4 w-4 rounded-md border-slate-300 text-blue-600"
                              checked={selected}
                              onChange={() => {
                                const current = filters.ta ? filters.ta.split(',') : [];
                                const next = selected ? current.filter(x => x !== pointName) : [...current, pointName];
                                onChange({ ta: next.join(',') });
                              }}
                            />
                            <span className="truncate text-sm font-bold">{pointName}</span>
                          </span>
                          <span className="text-[10px] font-black opacity-40">{point.trip_count}</span>
                        </label>
                      );
                    })}
                  </div>
                )}
              </section>
            )}
          </div>
        </div>
      </aside>
      <div className="fixed bottom-[calc(env(safe-area-inset-bottom)+16px)] left-1/2 z-50 flex w-[calc(100%-32px)] max-w-md -translate-x-1/2 items-center justify-between gap-1 rounded-full bg-[#0F172A] border border-white/10 p-1.5 text-white shadow-2xl lg:hidden">
        <MobileToolbarButton sheet="filter" icon="fa-sliders-h" label="Lọc" badge={activeFilterCount || ''} active={activeSheet === 'filter'} />
        <MobileToolbarButton sheet="sort" icon="fa-sort-amount-down" label="Sắp xếp" active={activeSheet === 'sort'} />
        <MobileToolbarButton sheet="time" icon="fa-clock" label="Giờ đi" active={activeSheet === 'time'} />
        <MobileToolbarButton sheet="company" icon="fa-bus" label="Nhà xe" badge={selectedCompanies.length || ''} active={activeSheet === 'company'} />
      </div>
      {mobileSheet}
    </>
  );
};

const TripImageSlider = ({ gallery, companyName }) => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const trackRef = useRef(null);
  const thumbnailsRef = useRef(null);

  const goToSlide = (idx) => {
    if (!trackRef.current) return;
    const width = trackRef.current.offsetWidth;
    trackRef.current.scrollTo({
      left: idx * width,
      behavior: 'smooth',
    });
    setCurrentIndex(idx);
    scrollThumbnailIntoView(idx);
  };

  const slidePrev = () => {
    const nextIdx = currentIndex === 0 ? gallery.length - 1 : currentIndex - 1;
    goToSlide(nextIdx);
  };

  const slideNext = () => {
    const nextIdx = currentIndex === gallery.length - 1 ? 0 : currentIndex + 1;
    goToSlide(nextIdx);
  };

  const handleScroll = () => {
    if (!trackRef.current) return;
    const scrollLeft = trackRef.current.scrollLeft;
    const width = trackRef.current.offsetWidth;
    if (width > 0) {
      const idx = Math.round(scrollLeft / width);
      if (idx !== currentIndex && idx >= 0 && idx < gallery.length) {
        setCurrentIndex(idx);
        scrollThumbnailIntoView(idx);
      }
    }
  };

  const scrollThumbnailIntoView = (idx) => {
    if (!thumbnailsRef.current) return;
    const thumbs = thumbnailsRef.current.querySelectorAll('.ol-card__thumbnail');
    const targetThumb = thumbs[idx];
    if (targetThumb) {
      const container = thumbnailsRef.current;
      const left = targetThumb.offsetLeft - container.offsetWidth / 2 + targetThumb.offsetWidth / 2;
      container.scrollTo({
        left: left,
        behavior: 'smooth',
      });
    }
  };

  useEffect(() => {
    const handleResize = () => {
      if (trackRef.current) {
        const width = trackRef.current.offsetWidth;
        trackRef.current.scrollLeft = currentIndex * width;
      }
    };
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, [currentIndex]);

  if (!gallery || gallery.length === 0) {
    return (
      <div className="ol-card__gallery-empty">
        <p>Hình ảnh nhà xe đang được cập nhật.</p>
      </div>
    );
  }

  return (
    <div className="w-full space-y-4">
      <div className="ol-card__slider">
        <div
          className="ol-card__slider-track"
          ref={trackRef}
          onScroll={handleScroll}
          style={{ scrollBehavior: 'smooth' }}
        >
          {gallery.map((img, i) => (
            <div key={i} className="ol-card__slider-slide">
              <img
                src={img.url || img.medium}
                alt={img.title || companyName}
                loading="lazy"
              />
              {img.title && img.title !== companyName && (
                <div className="ol-card__slider-caption">{img.title}</div>
              )}
            </div>
          ))}
        </div>

        {gallery.length > 1 && (
          <>
            <button
              type="button"
              className="ol-card__slider-btn ol-card__slider-btn--prev"
              onClick={slidePrev}
              aria-label="Slide trước"
            >
              <i className="fas fa-chevron-left"></i>
            </button>
            <button
              type="button"
              className="ol-card__slider-btn ol-card__slider-btn--next"
              onClick={slideNext}
              aria-label="Slide tiếp theo"
            >
              <i className="fas fa-chevron-right"></i>
            </button>
            <div className="ol-card__slider-counter">
              <span className="current">{currentIndex + 1}</span>/
              <span className="total">{gallery.length}</span>
            </div>
          </>
        )}
      </div>

      {gallery.length > 1 && (
        <div className="ol-card__thumbnails" ref={thumbnailsRef}>
          {gallery.map((img, i) => (
            <button
              key={i}
              type="button"
              className={`ol-card__thumbnail ${i === currentIndex ? 'active' : ''}`}
              onClick={() => goToSlide(i)}
              aria-label={`Xem ảnh ${i + 1}`}
            >
              <img src={img.medium || img.url} alt={img.title || companyName} loading="lazy" />
            </button>
          ))}
        </div>
      )}
    </div>
  );
};

const DetailTabs = ({ trip, gallery }) => {
  const [activeTab, setActiveTab] = useState('images');

  const [utilities, setUtilities] = useState(null);
  const [points, setPoints] = useState(null);
  const [policy, setPolicy] = useState(null);
  const [reviews, setReviews] = useState(null);
  const [reviewsPage, setReviewsPage] = useState(1);

  const [loadingStates, setLoadingStates] = useState({
    utilities: false,
    points: false,
    policy: false,
    reviews: false,
  });

  const loadUtilities = async () => {
    setLoadingStates((prev) => ({ ...prev, utilities: true }));
    try {
      const url = new URL(window.generic_data?.ajax_url || '/wp-admin/admin-ajax.php', window.location.origin);
      url.searchParams.append('action', 'get_bus_amenities');
      url.searchParams.append('seat_template_id', trip.seat_template_id || '');
      url.searchParams.append('partnerId', trip.partner?.partner_id || trip.partner_id || 'vexere');
      url.searchParams.append('company_id', trip.company_id || '');

      const res = await fetch(url);
      const json = await res.json();
      if (json.success && Array.isArray(json.data)) {
        setUtilities(json.data);
      } else {
        setUtilities([]);
      }
    } catch (e) {
      console.error(e);
      setUtilities([]);
    } finally {
      setLoadingStates((prev) => ({ ...prev, utilities: false }));
    }
  };

  const loadPoints = async () => {
    setLoadingStates((prev) => ({ ...prev, points: true }));
    try {
      const formData = new FormData();
      formData.append('action', 'get_info_ajax_company');
      formData.append('companyId', trip.company_id || '');
      formData.append('tripCode', trip.trip_id || '');
      formData.append('partnerId', trip.partner?.partner_id || trip.partner_id || 'vexere');
      formData.append('pickupDate', trip.pickup_date || '');
      formData.append('partnerName', (trip.partner?.partner_name || trip.partner?.partner_id || 'vexere').toLowerCase());
      const timeOnly = trip.departure_time || (trip.pickup_date ? (trip.pickup_date.includes('T') ? trip.pickup_date.split('T')[1]?.slice(0, 5) : (trip.pickup_date.includes(' ') ? trip.pickup_date.split(' ')[1]?.slice(0, 5) : '')) : '');
      formData.append('departureTime', timeOnly || '00:00');
      formData.append('wayId', trip.way_id || '');
      formData.append('bookingId', trip.booking_id || '');
      formData.append('fare', trip.fare || '');
      formData.append('to', trip.toId || trip.to_name || '');
      formData.append('from', trip.fromId || trip.from_name || '');

      const res = await fetch(window.generic_data?.ajax_url || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData,
      });
      const text = await res.text();
      try {
        const json = JSON.parse(text);
        setPoints({
          pickUpHtml: json.pickUpHtml || '',
          dropOffHtml: json.dropOffHtml || '',
          listCats: json.listCats || '',
        });
      } catch (e) {
        console.error('Failed to parse points JSON:', text);
        setPoints({ pickUpHtml: '', dropOffHtml: '', listCats: '' });
      }
    } catch (e) {
      console.error(e);
      setPoints({ pickUpHtml: '', dropOffHtml: '', listCats: '' });
    } finally {
      setLoadingStates((prev) => ({ ...prev, points: false }));
    }
  };

  const loadPolicy = async () => {
    setLoadingStates((prev) => ({ ...prev, policy: true }));
    try {
      const url1 = new URL(window.generic_data?.ajax_url || '/wp-admin/admin-ajax.php', window.location.origin);
      url1.searchParams.append('action', 'get_cancellation_policy');
      url1.searchParams.append('tripCode', trip.trip_id || '');
      url1.searchParams.append('partnerId', trip.partner?.partner_id || trip.partner_id || 'vexere');
      url1.searchParams.append('departureDate', trip.departure_date || trip.pickup_date || '');

      const url2 = new URL(window.generic_data?.ajax_url || '/wp-admin/admin-ajax.php', window.location.origin);
      url2.searchParams.append('action', 'get_policy_mapping');
      url2.searchParams.append('tripCode', trip.trip_id || '');
      url2.searchParams.append('seat_template_id', trip.seat_template_id || '');
      url2.searchParams.append('partnerId', trip.partner?.partner_id || trip.partner_id || 'vexere');
      url2.searchParams.append('company_id', trip.company_id || '');

      const [res1, res2] = await Promise.all([fetch(url1), fetch(url2)]);
      const html1 = await res1.text();
      const html2 = await res2.text();

      setPolicy((html1 + html2).trim());
    } catch (e) {
      console.error(e);
      setPolicy('');
    } finally {
      setLoadingStates((prev) => ({ ...prev, policy: false }));
    }
  };

  const loadReviews = async (page) => {
    setLoadingStates((prev) => ({ ...prev, reviews: true }));
    try {
      const url = new URL(window.generic_data?.ajax_url || '/wp-admin/admin-ajax.php', window.location.origin);
      url.searchParams.append('action', 'get_review_ajax_company');
      url.searchParams.append('companyId', trip.company_id || '');
      url.searchParams.append('partnerName', (trip.partner?.partner_name || trip.partner?.partner_id || 'vexere').toLowerCase());
      url.searchParams.append('page', page);

      const res = await fetch(url);
      const text = await res.text();
      try {
        const json = JSON.parse(text);
        setReviews({
          html: json.html || '',
          total: json.total || 0,
        });
      } catch (e) {
        console.error('Failed to parse reviews JSON:', text);
        setReviews({ html: '', total: 0 });
      }
    } catch (e) {
      console.error(e);
      setReviews({ html: '', total: 0 });
    } finally {
      setLoadingStates((prev) => ({ ...prev, reviews: false }));
    }
  };

  useEffect(() => {
    if (activeTab === 'utilities' && !utilities && !loadingStates.utilities) {
      loadUtilities();
    } else if (activeTab === 'points' && !points && !loadingStates.points) {
      loadPoints();
    } else if (activeTab === 'policy' && !policy && !loadingStates.policy) {
      loadPolicy();
    } else if (activeTab === 'reviews') {
      if (!reviews && !loadingStates.reviews) {
        loadReviews(1);
      }
      if (!points && !loadingStates.points) {
        loadPoints();
      }
    }
  }, [activeTab]);

  const tabs = [
    { id: 'images', label: 'Hình ảnh', icon: 'fa-images' },
    { id: 'utilities', label: 'Tiện ích', icon: 'fa-wifi' },
    { id: 'points', label: 'Điểm đón, trả', icon: 'fa-map-marker-alt' },
    { id: 'policy', label: 'Chính sách', icon: 'fa-file-contract' },
    { id: 'reviews', label: 'Đánh giá', icon: 'fa-star' },
  ];

  const LoadingSpinner = () => (
    <div className="flex flex-col items-center justify-center py-12 gap-3">
      <div className="h-10 w-10 premium-spinner"></div>
      <span className="text-xs font-black tracking-wider text-slate-400 uppercase">Đang tải dữ liệu...</span>
    </div>
  );

  const totalPages = reviews?.total || 0;

  const getVisiblePages = (current, total) => {
    if (total <= 7) {
      return Array.from({ length: total }, (_, i) => i + 1);
    }
    const pages = [];
    pages.push(1);
    let start = Math.max(2, current - 2);
    let end = Math.min(total - 1, current + 2);
    if (current <= 4) {
      end = 5;
    } else if (current >= total - 3) {
      start = total - 4;
    }
    if (start > 2) {
      pages.push('ellipsis-start');
    }
    for (let i = start; i <= end; i++) {
      pages.push(i);
    }
    if (end < total - 1) {
      pages.push('ellipsis-end');
    }
    pages.push(total);
    return pages;
  };

  return (
    <div className="dailyve-trip-details overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-sm">
      <div className="flex overflow-x-auto flex-nowrap [scrollbar-width:none] [-ms-overflow-style:none] [-webkit-overflow-scrolling:touch] [&::-webkit-scrollbar]:hidden border-b border-slate-50 bg-slate-50/50 p-2 md:flex-wrap md:overflow-x-visible">
        {tabs.map((tab) => (
          <button
            key={tab.id}
            onClick={() => setActiveTab(tab.id)}
            className={`flex shrink-0 items-center gap-2 rounded-2xl px-6 py-3 text-xs font-black uppercase tracking-wider transition-all ${activeTab === tab.id
              ? 'bg-white text-primary shadow-sm'
              : 'text-slate-400 hover:text-slate-600'
              }`}
          >
            <i className={`fas ${tab.icon}`}></i> {tab.label}
          </button>
        ))}
      </div>

      <div className="p-2 sm:p-8">
        {activeTab === 'images' && (
          <TripImageSlider gallery={gallery} companyName={trip.company_name} />
        )}

        {activeTab === 'utilities' && (
          <div>
            {loadingStates.utilities ? (
              <LoadingSpinner />
            ) : utilities ? (
              <div className="space-y-8 animate-in fade-in duration-300">
                {utilities.filter((item) => item.description).length > 0 && (
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {utilities
                      .filter((item) => item.description)
                      .map((item, i) => (
                        <div
                          key={i}
                          className="flex gap-4 rounded-3xl border border-slate-100 p-5 transition-all hover:border-primary-light/30 hover:bg-blue-50/20"
                        >
                          <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50">
                            {item.icon_url ? (
                              <img src={item.icon_url} className="h-7 w-7 object-contain" alt={item.name} rel='nofollow noreferrer' />
                            ) : (
                              <i className="fas fa-wifi text-primary text-lg"></i>
                            )}
                          </div>
                          <div className="space-y-1">
                            <h5 className="font-display text-sm font-black text-slate-900 uppercase tracking-wide">
                              {item.name}
                            </h5>
                            <p className="text-xs font-medium leading-relaxed text-slate-500">{item.description}</p>
                          </div>
                        </div>
                      ))}
                  </div>
                )}

                {utilities.filter((item) => !item.description).length > 0 && (
                  <div className="flex flex-wrap gap-3">
                    {utilities
                      .filter((item) => !item.description)
                      .map((item, i) => (
                        <div
                          key={i}
                          className="flex items-center gap-2.5 rounded-lg border border-slate-100 bg-slate-50/30 px-5 py-2.5 transition-all hover:bg-blue-50/30 hover:border-primary-light/20"
                        >
                          {item.icon_url && (
                            <img src={item.icon_url} className="h-4 w-4 object-contain" alt={item.name} />
                          )}
                          <span className="text-xs font-black text-slate-700 tracking-wide">{item.name}</span>
                        </div>
                      ))}
                  </div>
                )}

                {utilities.length === 0 && (
                  <div className="text-center py-6 text-sm font-semibold text-slate-400">
                    Dailyve sẽ sớm cập nhật thông tin tiện ích cho nhà xe này.
                  </div>
                )}
              </div>
            ) : null}
          </div>
        )}

        {activeTab === 'points' && (
          <div>
            {loadingStates.points ? (
              <LoadingSpinner />
            ) : points ? (
              <div className="space-y-8 animate-in fade-in duration-300">
                <div className="rounded-3xl border border-amber-100 bg-amber-50/50 p-6">
                  <h4 className="flex items-center gap-2 font-display text-sm font-black uppercase tracking-wider text-amber-800">
                    <i className="fas fa-info-circle"></i> Lưu ý
                  </h4>
                  <p className="mt-2 text-xs font-semibold leading-relaxed text-amber-700">
                    Các mốc thời gian đón, trả bên dưới là thời gian dự kiến. Lịch trình thực tế có thể thay đổi tùy thuộc
                    vào tình hình giao thông.
                  </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div className="space-y-4">
                    <h4 className="flex items-center gap-2 font-display text-base font-black uppercase tracking-wider text-slate-800 border-b border-slate-100 pb-3">
                      <span className="flex h-6 w-6 items-center justify-center rounded-lg bg-success/10 text-success text-[10px]">
                        <i className="fas fa-map-marker-alt"></i>
                      </span>
                      Điểm đón
                    </h4>
                    <div className="points-timeline-container">
                      <ul
                        className="points-timeline-list pickup-list animate-in fade-in duration-300"
                        dangerouslySetInnerHTML={{
                          __html: points.pickUpHtml || '<li class="text-slate-400 font-bold">Chưa có thông tin điểm đón.</li>',
                        }}
                      />
                    </div>
                  </div>

                  <div className="space-y-4">
                    <h4 className="flex items-center gap-2 font-display text-base font-black uppercase tracking-wider text-slate-800 border-b border-slate-100 pb-3">
                      <span className="flex h-6 w-6 items-center justify-center rounded-lg bg-danger/10 text-danger text-[10px]">
                        <i className="fas fa-map-marker-alt"></i>
                      </span>
                      Điểm trả
                    </h4>
                    <div className="points-timeline-container">
                      <ul
                        className="points-timeline-list dropoff-list animate-in fade-in duration-300"
                        dangerouslySetInnerHTML={{
                          __html: points.dropOffHtml || '<li class="text-slate-400 font-bold">Chưa có thông tin điểm trả.</li>',
                        }}
                      />
                    </div>
                  </div>
                </div>
              </div>
            ) : null}
          </div>
        )}

        {activeTab === 'policy' && (
          <div>
            {loadingStates.policy ? (
              <LoadingSpinner />
            ) : policy ? (
              <div className="max-w-3xl space-y-6 animate-in fade-in duration-300">
                <h4 className="font-display text-lg font-black text-slate-900 uppercase tracking-wide">
                  Chính sách & Quy định
                </h4>
                <div
                  className="prose prose-slate max-w-none text-sm font-semibold leading-relaxed text-slate-600 bg-slate-50/50 rounded-3xl md:p-6 p-2 border border-slate-100
                    prose-p:mb-4 prose-p:last:mb-0
                    prose-strong:text-slate-900 prose-strong:font-black
                    prose-ul:list-disc prose-ul:pl-5 prose-ul:space-y-2"
                  dangerouslySetInnerHTML={{ __html: policy }}
                />
              </div>
            ) : (
              <div className="text-center py-6 text-sm font-semibold text-slate-400">
                Chưa có thông tin chính sách từ nhà xe.
              </div>
            )}
          </div>
        )}

        {activeTab === 'reviews' && (
          <div>
            {loadingStates.reviews ? (
              <LoadingSpinner />
            ) : reviews ? (
              <div className="space-y-8 animate-in fade-in duration-300">
                <div className="flex flex-col md:flex-row gap-6 items-center md:items-stretch">
                  <div className="flex flex-col items-center justify-center rounded-3xl bg-blue-50 px-8 py-6 text-center shrink-0 min-w-[150px]">
                    <div className="flex items-center gap-1.5 font-display text-5xl font-black text-primary">
                      <i className="fas fa-star text-yellow-400 text-3xl"></i>
                      {trip.ratings?.overall || 0}
                    </div>
                    <div className="mt-2 text-[10px] font-black uppercase tracking-widest text-primary/60">
                      Điểm đánh giá
                    </div>
                    <div className="mt-1 text-xs font-bold text-slate-400">({trip.ratings?.comments || 0} đánh giá)</div>
                  </div>

                  {loadingStates.points ? (
                    <div className="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 gap-4 animate-pulse pt-2">
                      {[1, 2, 3, 4].map((i) => (
                        <div key={i} className="flex flex-col gap-2">
                          <div className="h-4 w-24 bg-slate-100 rounded-md"></div>
                          <div className="flex items-center gap-3">
                            <div className="h-2 flex-1 bg-slate-100 rounded-full"></div>
                            <div className="h-4 w-6 bg-slate-100 rounded-md"></div>
                          </div>
                        </div>
                      ))}
                    </div>
                  ) : points?.listCats ? (
                    <div
                      className="flex-1 w-full reviews-criteria-container"
                      dangerouslySetInnerHTML={{ __html: points.listCats }}
                    />
                  ) : null}
                </div>

                <div className="border-t border-slate-100 pt-6">
                  <h4 className="font-display text-base font-black uppercase tracking-wider text-slate-800 mb-6">
                    Nhận xét từ hành khách
                  </h4>

                  <div
                    className="space-y-4 max-h-[500px] overflow-y-auto pr-2 scrollbar-thin
                      prose prose-slate max-w-none text-sm font-medium leading-relaxed"
                    dangerouslySetInnerHTML={{
                      __html: reviews.html || '<p class="text-slate-400 font-bold text-center py-6">Chưa có nhận xét nào.</p>',
                    }}
                  />

                  {totalPages > 1 && (
                    <div className="mt-8 flex items-center justify-center gap-2 animate-in fade-in duration-300">
                      {/* Prev Page Button */}
                      <button
                        disabled={reviewsPage === 1}
                        onClick={() => {
                          const prevPage = reviewsPage - 1;
                          setReviewsPage(prevPage);
                          loadReviews(prevPage);
                        }}
                        className="flex h-9 w-9 min-h-[36px] items-center justify-center rounded-xl bg-slate-50 text-slate-600 transition-all hover:bg-slate-100 disabled:pointer-events-none disabled:opacity-30"
                      >
                        <i className="fas fa-chevron-left text-[10px]"></i>
                      </button>

                      {/* Truncated Page List */}
                      {getVisiblePages(reviewsPage, totalPages).map((page, index) => {
                        if (page === 'ellipsis-start' || page === 'ellipsis-end') {
                          return (
                            <span
                              key={`ellipsis-${index}`}
                              className="flex h-9 w-6 items-center justify-center text-slate-400 text-xs font-black"
                            >
                              ...
                            </span>
                          );
                        }

                        return (
                          <button
                            key={page}
                            onClick={() => {
                              setReviewsPage(page);
                              loadReviews(page);
                            }}
                            className={`flex h-9 w-9 min-h-[36px] items-center justify-center rounded-xl text-xs font-black transition-all ${reviewsPage === page
                              ? 'bg-primary text-white shadow-md'
                              : 'bg-slate-50 text-slate-600 hover:bg-slate-100'
                              }`}
                          >
                            {page}
                          </button>
                        );
                      })}

                      {/* Next Page Button */}
                      <button
                        disabled={reviewsPage === totalPages}
                        onClick={() => {
                          const nextPage = reviewsPage + 1;
                          setReviewsPage(nextPage);
                          loadReviews(nextPage);
                        }}
                        className="flex h-9 w-9 min-h-[36px] items-center justify-center rounded-xl bg-slate-50 text-slate-600 transition-all hover:bg-slate-100 disabled:pointer-events-none disabled:opacity-30"
                      >
                        <i className="fas fa-chevron-right text-[10px]"></i>
                      </button>
                    </div>
                  )}
                </div>
              </div>
            ) : null}
          </div>
        )}
      </div>
    </div>
  );
};

const TripCard = ({ trip, stepTicket, setStepTicket, filters, setFilters, syncUrl }) => {
  const [showDetails, setShowDetails] = useState(false);
  const [isBooking, setIsBooking] = useState(false);
  const [showNoteModal, setShowNoteModal] = useState(false);

  useEffect(() => {
    if (isBooking && trip.important_notification?.content) {
      setShowNoteModal(true);
    }
  }, [isBooking]);

  const gallery =
    Array.isArray(trip.company_gallery) && trip.company_gallery.length > 0
      ? trip.company_gallery
      : [{ url: trip.company_logo, medium: trip.company_logo, title: trip.company_name }];
  const galleryImage = gallery.find((image) => image?.medium || image?.url);
  const primaryImage = normalizeImageUrl(trip.image)
    || normalizeImageUrl(galleryImage?.medium || galleryImage?.url)
    || normalizeImageUrl(trip.company_logo);
  const availableSeats = Number(trip.available_seat || 0);
  const hasDiscount = Number(trip.fare_discount || 0) > 0 && Number(trip.fare_original || 0) > 0;
  const hasMultipleFares = trip.fare != null && trip.fare_max != null && Number(trip.fare_max) > Number(trip.fare_original || trip.fare);

  const partnerId = trip.partner?.partner_id || trip.partner_id || '';
  const partnerName = trip.partner?.partner_name || trip.partner_name || '';

  return (
    <li className={`dailyve-trip-card group relative flex flex-col overflow-hidden rounded-[18px] border bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-premium ${isBooking ? 'border-primary ring-1 ring-primary/20' : 'border-slate-100 hover:border-primary/70'
      }`}>
      {/* Important Notification Modal */}
      {showNoteModal && trip.important_notification?.content && createPortal(
        <div className="dailyve-important-modal fixed inset-0 z-[100] flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-all duration-300" onClick={() => setShowNoteModal(false)}></div>
          <div className="relative w-full max-w-xl transform overflow-hidden rounded-xl bg-white border border-slate-200 shadow-premium animate-in zoom-in-95 duration-300 flex flex-col max-h-[80vh]">
            {/* Header */}
            <div className="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
              <div className="flex items-center gap-3.5">
                <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-500 border border-amber-100/50 shadow-sm shrink-0">
                  <i className="fas fa-exclamation-triangle text-base"></i>
                </div>
                <div>
                  <h3 className="font-display text-lg font-semibold tracking-tight text-slate-900 leading-snug">Thông báo quan trọng</h3>
                  <p className="text-xs text-slate-500 font-medium mt-0.5">Vui lòng lưu ý kỹ quy định của nhà xe trước khi đặt vé</p>
                </div>
              </div>
              <button
                onClick={() => setShowNoteModal(false)}
                className="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-400 border border-slate-100 transition-all hover:bg-slate-100 hover:text-slate-600 active:scale-95 duration-200 shrink-0"
              >
                <i className="fas fa-times text-sm"></i>
              </button>
            </div>

            {/* Body Content */}
            <div className="p-6 md:p-8 overflow-y-auto pr-4 scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent flex-1 bg-white">
              <div
                className="prose prose-slate max-w-none text-slate-600 font-medium text-sm leading-relaxed
                  prose-p:mb-4 prose-p:last:mb-0
                  prose-ul:list-disc prose-ul:pl-5 prose-ul:space-y-2
                  prose-strong:text-slate-900 prose-strong:font-semibold"
                dangerouslySetInnerHTML={{ __html: trip.important_notification.content }}
              />
            </div>

            {/* Footer Action */}
            <div className="px-6 pb-6 pt-4 md:px-8 md:pb-8 md:pt-4 shrink-0 border-t border-slate-100 bg-slate-50/50">
              <button
                onClick={() => setShowNoteModal(false)}
                className="w-full rounded-md bg-primary hover:bg-primary-active py-3 text-sm font-semibold uppercase tracking-wider text-white shadow-md shadow-primary/10 transition-all duration-200 active:scale-[0.98]"
              >
                ĐÃ HIỂU & TIẾP TỤC
              </button>
            </div>
          </div>
        </div>,
        document.body
      )}
      <div className="grid gap-5 p-4 sm:p-5 lg:grid-cols-[140px_minmax(0,1fr)_200px] lg:items-center lg:gap-7 xl:grid-cols-[150px_minmax(0,1fr)_210px]">
        {/* Logo & Confirm */}
        <div className="relative mx-auto w-full max-w-[150px] shrink-0 sm:mx-0 lg:max-w-none">
          <div className="aspect-[4/3] overflow-hidden rounded-lg border border-slate-100 bg-slate-100 shadow-sm">
            {primaryImage ? (
              <img
                src={primaryImage}
                className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                alt={trip.company_name}
                rel="nofollow noreferrer"
              />
            ) : (
              <div className="flex h-full w-full items-center justify-center text-3xl text-slate-300">
                <i className="fas fa-bus"></i>
              </div>
            )}
          </div>
          <div className="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-success px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-white shadow-md">
            <i className="fas fa-shield-alt mr-1"></i> Xác nhận tức thì
          </div>
        </div>

        {/* Info Content */}
        <div className="min-w-0 space-y-4">
          <div className="flex min-w-0 flex-col gap-3">
            <div className="min-w-0">
              <h3 className="truncate font-display text-[22px] font-semibold tracking-tight text-slate-900 sm:text-2xl lg:text-[24px]">{trip.company_name}</h3>
              <div className="mt-2 flex flex-wrap items-center gap-2 sm:gap-3">
                <span className="flex max-w-full items-center truncate rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold tracking-wide text-slate-600 sm:text-[11px]">
                  {trip.vehicle_type}
                </span>
                <span className="flex items-center text-sm font-bold text-warning">
                  <i className="fas fa-star mr-1.5"></i> {trip.ratings?.overall || 0}
                  <span className="ml-2 font-medium text-slate-400">({trip.ratings?.comments || 0} đánh giá)</span>
                </span>
              </div>
            </div>

            {/* Regular Notification as Tooltip */}
            {trip.notification?.label && (
              <div className="relative group/notify w-fit max-w-full">
                <div className="flex max-w-full cursor-pointer items-center gap-2 rounded-full border border-amber-100 bg-amber-50 px-3 py-2 transition-all hover:bg-amber-100">
                  <span className="relative flex h-2 w-2">
                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span className="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                  </span>
                  <span className="truncate text-[11px] font-black uppercase tracking-wider text-amber-700">{trip.notification.label}</span>
                </div>

                {/* Tooltip content */}
                <div className="invisible absolute bottom-full left-0 z-50 mb-3 w-72 max-w-[calc(100vw-2rem)] opacity-0 transition-all duration-300 group-hover/notify:visible group-hover/notify:opacity-100 sm:left-1/2 sm:-translate-x-1/2">
                  <div className="relative bg-slate-900 text-white p-4 rounded-2xl shadow-2xl text-xs font-medium leading-relaxed">
                    <div className="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-slate-900 rotate-45"></div>
                    <div className="flex items-start gap-3">
                      <i className="fas fa-info-circle text-amber-400 mt-0.5"></i>
                      <div>{trip.notification.content || trip.notification.label}</div>
                    </div>
                  </div>
                </div>
              </div>
            )}
          </div>

          <div className="grid grid-cols-[minmax(0,1fr)_minmax(82px,120px)_minmax(0,1fr)] items-start gap-2 sm:grid-cols-[minmax(0,1fr)_minmax(110px,150px)_minmax(0,1fr)] sm:gap-4">
            <div className="min-w-0">
              <div className="flex items-center gap-2">
                <span className="font-display text-2xl font-semibold text-slate-950 sm:text-[24px]">{formatTime(trip.pickup_date)}</span>
                <span className="h-2 w-2 shrink-0 rounded-full bg-primary"></span>
              </div>
              <div className="mt-1 truncate text-[11px] font-semibold tracking-wide text-slate-600 sm:text-xs">{trip.from_name}</div>
            </div>

            <div className="relative mt-4 flex min-w-0 items-center justify-center">
              <span className="absolute left-0 right-0 top-1/2 border-t border-dashed border-blue-100"></span>
              <span className="relative max-w-full rounded-full border border-blue-100 bg-blue-50 px-2 py-1 text-center text-[9px] font-black leading-tight text-primary sm:px-3 sm:text-[10px]">
                {routeDuration(trip.pickup_date, trip.arrival_date) || '...'}
              </span>
            </div>

            <div className="min-w-0 text-right">
              <div className="flex items-center justify-end gap-2">
                <i className="fas fa-map-marker-alt text-xs text-primary"></i>
                <span className="font-display text-2xl font-semibold text-slate-950 sm:text-[24px]">{formatTime(trip.arrival_date)}</span>
              </div>
              <div className="mt-1 truncate text-[11px] font-semibold tracking-wide text-slate-600 sm:text-xs">{trip.to_name}</div>
            </div>
          </div>
        </div>

        {/* Price & Action */}
        <div className="flex min-w-0 flex-col justify-between gap-4 border-t border-slate-100 pt-4 lg:h-full lg:border-l lg:border-t-0 lg:pl-6 lg:pt-0">
          <div className="text-left sm:text-right">
            {hasDiscount && (
              <div className="text-sm font-bold text-slate-500 line-through">
                {formatCurrency(trip.fare_original)}
              </div>
            )}
            <div className="font-display text-2xl font-bold tracking-tight text-primary-dark sm:text-3xl">
              {hasMultipleFares ? <span className="text-xs sm:text-sm font-bold text-slate-400 mr-1 uppercase tracking-wider">Từ</span> : ''}{formatCurrency(trip.fare)}
            </div>
            <div className={`mt-1 text-xs font-semibold uppercase tracking-wide ${availableSeats <= 5 ? 'text-danger' : 'text-success'}`}>
              {availableSeats <= 5 ? `Chỉ còn ${trip.available_seat} ghế` : `Còn ${trip.available_seat} chỗ trống`}
            </div>
          </div>

          <div className="grid grid-cols-2 gap-2 lg:grid-cols-1">
            <button
              onClick={() => setShowDetails(!showDetails)}
              className="flex min-h-12 items-center justify-center rounded-md border-2 border-blue-100 bg-white px-3 text-xs font-black uppercase text-primary transition-all hover:border-primary hover:bg-blue-50 active:scale-95"
            >
              CHI TIẾT
            </button>
            <button
              style={{ background: 'var(--grad-primary)' }}
              onClick={() => {
                setShowDetails(false);
                setIsBooking(true);
              }}
              className="flex min-h-12 items-center justify-center rounded-md px-3 text-xs font-black uppercase text-white shadow-lg shadow-primary/20 transition-all hover:opacity-90 active:scale-95"
            >
              CHỌN CHUYẾN
            </button>
          </div>
        </div>
      </div>

      {isBooking && (
        <div className="border-t-2 border-primary/10 bg-white">
          <div className="p-4 sm:p-6 lg:p-8">
            <SeatSelection
              trip={trip}
              legIndex={stepTicket}
              onCancel={() => setIsBooking(false)}
              onComplete={(ticket) => {
                const searchParams = new URLSearchParams(window.location.search);
                if (searchParams.get('returnDate') && stepTicket === 0) {
                  const nextStep = 1;
                  setStepTicket(nextStep);
                  const nextFilters = { ...filters, fa: '', ta: '', rating: '' };
                  setFilters(nextFilters);
                  syncUrl(nextFilters, nextStep);
                  setIsBooking(false);
                  window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                  window.location.href = window.location.origin + '/bookingconfirmation';
                }
              }}
            />
          </div>
        </div>
      )}

      {showDetails && !isBooking && (
        <div className="border-t border-slate-50 bg-slate-50/30 p-4 sm:p-6 lg:p-8">
          <DetailTabs trip={trip} gallery={gallery} />
        </div>
      )}
    </li>
  );
};

const TripList = () => {
  const initialFiltersRef = useRef(null);
  if (initialFiltersRef.current === null) {
    initialFiltersRef.current = getInitialFilters();
  }

  const [queryString, setQueryString] = useState(window.location.search);
  const [filters, setFilters] = useState(() => initialFiltersRef.current);

  // Initialize stepTicket from URL or default to 0
  const [stepTicket, setStepTicket] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return parseInt(params.get('step') || '0', 10);
  });

  const hasClearedRef = React.useRef(false);

  const [trips, setTrips] = useState([]);
  const [statistics, setStatistics] = useState({});
  const [paging, setPaging] = useState({});
  const [nextCursor, setNextCursor] = useState('');
  const [loading, setLoading] = useState(() => Boolean(initialFiltersRef.current.from && initialFiltersRef.current.to));
  const [loadingMore, setLoadingMore] = useState(false);
  const [error, setError] = useState('');
  const [priceRange, setPriceRange] = useState('all');
  const [locations, setLocations] = useState([]);

  useEffect(() => {
    fetchLocationsWithCache(setLocations);
  }, []);

  const locationMap = useMemo(() => buildLocationMap(locations), [locations]);

  useEffect(() => {
    if (locations.length === 0) return;

    const fromVal = filters.from;
    const toVal = filters.to;

    const isValidFrom = fromVal && locationMap[fromVal];
    const isValidTo = toVal && locationMap[toVal];

    let resolvedFrom = fromVal;
    let resolvedTo = toVal;
    let needsSync = false;

    if (!isValidFrom && (filters.nameFrom || filters.from)) {
      const searchName = filters.nameFrom || filters.from;
      const found = resolveLocationInput(locations, locationMap, '', searchName);
      if (found) {
        resolvedFrom = found.id;
        needsSync = true;
      }
    }

    if (!isValidTo && (filters.nameTo || filters.to)) {
      const searchName = filters.nameTo || filters.to;
      const found = resolveLocationInput(locations, locationMap, '', searchName);
      if (found) {
        resolvedTo = found.id;
        needsSync = true;
      }
    }

    if (needsSync) {
      const nextFilters = {
        ...filters,
        from: resolvedFrom,
        to: resolvedTo,
        nameFrom: locationMap[resolvedFrom]?.name || filters.nameFrom,
        nameTo: locationMap[resolvedTo]?.name || filters.nameTo,
      };
      setFilters(nextFilters);
      syncUrl(nextFilters);
    }
  }, [locations, locationMap]);

  const syncUrl = (next, step = stepTicket) => {
    const params = buildQuery(next);
    if (step > 0) params.set('step', step);
    window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);
    setQueryString(window.location.search);
  };



  const updateFilters = (patch) => {
    const next = { ...filters, ...patch };
    setFilters(next);
    syncUrl(next);
  };

  const fetchTrips = (append = false, cursor = '') => {
    if (!filters.from || !filters.to) return;

    const searchParams = new URLSearchParams(window.location.search);
    const returnDate = searchParams.get('returnDate');

    const fetchFilters = { ...filters };
    if (stepTicket === 1 && returnDate) {
      // Swap from and to for return leg
      const from = fetchFilters.from;
      fetchFilters.from = fetchFilters.to;
      fetchFilters.to = from;
      fetchFilters.date = formatDateInput(returnDate);
    }

    const params = buildQuery(fetchFilters, cursor ? { cursor } : {});
    const endpoint = `/wp-json/api/v1/trips?${params.toString()}`;

    setError('');
    append ? setLoadingMore(true) : setLoading(true);

    fetch(endpoint)
      .then((response) => response.json().then((body) => ({ ok: response.ok, body })))
      .then(({ ok, body }) => {
        if (!ok || body.success === false) {
          throw new Error(body.message || body.data?.message || 'Không thể tải dữ liệu chuyến xe.');
        }

        const payload = body.data || {};
        setTrips((current) => (append ? [...current, ...(payload.items || [])] : payload.items || []));
        setStatistics(payload.statistics || {});
        setPaging(payload.paging || {});
        setNextCursor(payload.nextCursor || '');

        // Handle operator_id mapping from url parameters to local statistics company list
        if (!append && payload.statistics?.companies?.data) {
          const params = new URLSearchParams(window.location.search);
          const operatorIdStr = params.get('operator_id');
          if (operatorIdStr) {
            const operatorId = Number(operatorIdStr);
            const foundCompany = payload.statistics.companies.data.find(
              (c) => Number(c.vexere_company_id) === operatorId
            );

            if (foundCompany) {
              const nextFilters = { ...filters, companies: foundCompany.id };
              setFilters(nextFilters);
              syncUrl(nextFilters);
            } else {
              // Silently remove operator_id from the address bar to avoid refetching
              const cleanParams = new URLSearchParams(window.location.search);
              cleanParams.delete('operator_id');
              window.history.replaceState({}, '', `${window.location.pathname}?${cleanParams.toString()}`);
            }
          }
        }
      })
      .catch((fetchError) => {
        setError(fetchError.message || 'Không thể tải dữ liệu chuyến xe.');
        if (!append) {
          setTrips([]);
        }
      })
      .finally(() => {
        append ? setLoadingMore(false) : setLoading(false);
      });
  };

  useEffect(() => {
    if (stepTicket === 0 && !hasClearedRef.current) {
      hasClearedRef.current = true;
      const formData = new FormData();
      formData.append('action', 'clear_tickets');
      formData.append('nonce', window.generic_data?.nonce);
      fetch(window.generic_data?.ajax_url || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData
      });
    }
    fetchTrips(false);
  }, [queryString, stepTicket]);

  const handleNewSearch = (payload) => {
    const next = {
      ...filters,
      from: payload.from,
      to: payload.to,
      nameFrom: payload.nameFrom || '',
      nameTo: payload.nameTo || '',
      date: payload.date,
      returnDate: payload.returnDate || '',
      service: payload.service || filters.service || 'bus',
      companies: '',
      fa: '',
      ta: '',
      rating: '',
    };
    setFilters(next);

    // Reset cleared flag for new search
    hasClearedRef.current = false;

    const params = buildQuery(next);
    if (payload.nameFrom) params.set('nameFrom', payload.nameFrom);
    if (payload.nameTo) params.set('nameTo', payload.nameTo);
    // Ensure step is reset to 0 for new search
    params.delete('step');
    setStepTicket(0);

    window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);
    setQueryString(window.location.search);
  };

  const visibleTrips = useMemo(() => trips.filter((trip) => priceMatches(trip, priceRange)), [trips, priceRange]);
  const total = paging.totalItems ?? trips.length;
  const routeTitle =
    trips.length > 0 ? `${trips[0].from_name || 'Điểm đi'} đi ${trips[0].to_name || 'Điểm đến'}` : 'Tìm chuyến xe';
  const filterOptionCacheKey = (() => {
    const returnDate = filters.returnDate || new URLSearchParams(window.location.search).get('returnDate') || '';
    const fromForLeg = stepTicket === 1 ? filters.to : filters.from;
    const toForLeg = stepTicket === 1 ? filters.from : filters.to;
    const dateForLeg = stepTicket === 1 && returnDate ? formatDateInput(returnDate) : filters.date;

    return [filters.service || 'bus', fromForLeg, toForLeg, dateForLeg, stepTicket].join('|');
  })();

  return (
    <div className="dailyve-trip-list min-h-screen overflow-x-hidden bg-slate-50/50">
      <section className="dailyve-trip-hero relative overflow-visible bg-white pb-8 pt-6 md:pb-12 md:pt-10">
        <div className="relative mx-auto max-w-7xl px-3 sm:px-4">
          <div className="mb-6 text-center md:mb-8 md:text-left">
            <p className="inline-block rounded-full bg-blue-50 px-4 py-1.5 text-xs font-black uppercase tracking-widest text-blue-600">
              <i className="fas fa-route mr-2"></i> Hệ thống đặt vé Dailyve
            </p>
            <h1 className="mt-4 font-display text-2xl text-center font-black leading-snug tracking-tight text-slate-900 sm:text-3xl md:text-5xl">
              Khám phá <span className="text-blue-600">hành trình</span> của bạn
            </h1>
          </div>

          <SearchForm
            className="dailyve-search--compact"
            initialService={filters.service || 'bus'}
            onSearch={handleNewSearch}
          />
        </div>
      </section>

      <section className="mx-auto grid max-w-7xl gap-5 px-3 pb-28 pt-5 sm:px-4 lg:grid-cols-[280px_1fr] lg:py-5">
        <FilterPanel
          filters={filters}
          statistics={statistics}
          priceRange={priceRange}
          onPriceRange={setPriceRange}
          onChange={updateFilters}
          resultCount={visibleTrips.length}
          cacheKey={filterOptionCacheKey}
        />

        <main className="order-1 min-w-0 lg:order-2">
          <div className="dailyve-results-summary mb-5 flex flex-col justify-between gap-4 rounded-[18px] border border-slate-100 bg-white p-4 shadow-sm sm:p-5 md:flex-row md:items-center">
            <div>
              <div className="flex items-center gap-2">
                <h2 className="min-w-0 break-words text-lg font-bold leading-snug text-slate-900 sm:text-xl">
                  {stepTicket === 0 ? 'Chiều đi: ' : 'Chiều về: '}
                  {filters.nameFrom && filters.nameTo ? (
                    stepTicket === 0 ? `${filters.nameFrom} → ${filters.nameTo}` : `${filters.nameTo} → ${filters.nameFrom}`
                  ) : (filters.from && filters.to ? (
                    stepTicket === 0 ? `${filters.from} → ${filters.to}` : `${filters.to} → ${filters.from}`
                  ) : 'Tìm chuyến xe')}
                </h2>
                {loading && <div className="h-2 w-2 animate-ping rounded-full bg-blue-500"></div>}
              </div>
              <p className="mt-1 text-sm font-normal text-slate-400">
                {(() => {
                  const searchParams = new URLSearchParams(window.location.search);
                  const displayDate = (stepTicket === 1 && searchParams.get('returnDate'))
                    ? searchParams.get('returnDate')
                    : filters.date;
                  return displayDate ? `Ngày ${displayDate}` : 'Chọn ngày đi';
                })()} • {loading ? 'Đang tìm kiếm...' : `${total} chuyến phù hợp`}
              </p>
            </div>
            <div className="flex items-center gap-2 rounded-2xl bg-blue-50 px-4 py-2 text-xs font-black text-blue-600">
              <i className="fas fa-shield-alt"></i> Giá vé chính thức & Cam kết có chỗ
            </div>
          </div>

          {!filters.from || !filters.to ? (
            <div className="dailyve-empty-state flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white py-20 px-10 text-center">
              <div className="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-3xl text-slate-300">
                <i className="fas fa-search"></i>
              </div>
              <h3 className="text-lg font-black text-slate-900">Sẵn sàng tìm chuyến?</h3>
              <p className="mt-2 max-w-xs text-sm font-bold text-slate-400">Chọn điểm đi, điểm đến và ngày khởi hành để xem các chuyến xe tốt nhất dành cho bạn.</p>
            </div>
          ) : error ? (
            <div className="rounded-3xl border border-red-100 bg-red-50 p-6 text-center">
              <div className="text-red-500 text-3xl mb-3"><i className="fas fa-exclamation-triangle"></i></div>
              <p className="text-sm font-bold text-red-700">{error}</p>
            </div>
          ) : loading ? (
            <div className="grid gap-5">
              {Array.from({ length: 3 }).map((_, index) => (
                <TripSkeleton key={index} />
              ))}
            </div>
          ) : visibleTrips.length > 0 ? (
            <>
              <div className="grid gap-5">
                {visibleTrips.map((trip) => (
                  <TripCard
                    key={`${trip.trip_id}-${trip.pickup_date}`}
                    trip={trip}
                    stepTicket={stepTicket}
                    setStepTicket={setStepTicket}
                    filters={filters}
                    setFilters={setFilters}
                    syncUrl={syncUrl}
                  />
                ))}
              </div>

              {nextCursor && paging.hasMore && (
                <div className="mt-8 flex justify-center">
                  <button
                    type="button"
                    className="dailyve-load-more group flex items-center gap-3 rounded-2xl border-2 border-blue-100 bg-white px-8 py-4 text-sm font-black text-blue-600 shadow-sm transition-all hover:border-blue-500 hover:bg-blue-50 active:scale-95 disabled:cursor-wait disabled:opacity-60"
                    disabled={loadingMore}
                    onClick={() => fetchTrips(true, nextCursor)}
                  >
                    {loadingMore ? (
                      <><i className="fas fa-circle-notch animate-spin"></i> Đang tải...</>
                    ) : (
                      <>Xem thêm {total - trips.length} chuyến xe <i className="fas fa-chevron-down transition-transform group-hover:translate-y-1"></i></>
                    )}
                  </button>
                </div>
              )}
            </>
          ) : (
            <div className="dailyve-empty-state flex flex-col items-center justify-center rounded-3xl border border-slate-100 bg-white py-20 px-10 text-center shadow-sm">
              <div className="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-3xl text-slate-300">
                <i className="fas fa-bus"></i>
              </div>
              <h3 className="text-lg font-black text-slate-900">Không tìm thấy chuyến xe</h3>
              <p className="mt-2 max-w-xs text-sm font-bold text-slate-400">Rất tiếc, chúng tôi không tìm thấy chuyến xe nào phù hợp với yêu cầu của bạn. Vui lòng thử lại với ngày khác hoặc nhà xe khác.</p>
            </div>
          )}
        </main>
      </section>
    </div>
  );
};

export default TripList;
