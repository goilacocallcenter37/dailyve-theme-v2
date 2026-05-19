import React, { useEffect, useMemo, useState } from 'react';
import { createPortal } from 'react-dom';
import SeatSelection from './SeatSelection';
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

const TIME_OPTIONS = [
  { value: '00:00-23:59', label: 'Cả ngày' },
  { value: '00:00-06:00', label: 'Đêm khuya' },
  { value: '06:00-12:00', label: 'Buổi sáng' },
  { value: '12:00-18:00', label: 'Buổi chiều' },
  { value: '18:00-23:59', label: 'Buổi tối' },
];

const PRICE_OPTIONS = [
  { value: 'all', label: 'Tất cả giá' },
  { value: 'under-200', label: 'Dưới 200.000đ' },
  { value: '200-400', label: '200.000đ - 400.000đ' },
  { value: 'over-400', label: 'Trên 400.000đ' },
];

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

const FilterPanel = ({ filters, statistics, priceRange, onPriceRange, onChange }) => {
  const companies = statistics?.companies?.data || [];
  const vehicleTypes = statistics?.vehicle_types || [];
  const selectedCompanies = filters.companies ? filters.companies.split(',').filter(Boolean) : [];

  const toggleCompany = (companyId) => {
    const next = new Set(selectedCompanies);
    if (next.has(String(companyId))) {
      next.delete(String(companyId));
    } else {
      next.add(String(companyId));
    }

    onChange({ companies: Array.from(next).join(',') });
  };

  return (
    <aside className="dailyve-filter-panel order-2 grid gap-4 lg:order-1 lg:sticky lg:top-24 lg:self-start">
      <div className="dailyve-filter-card overflow-hidden rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div className="mb-6 flex items-center justify-between border-b border-slate-50 pb-4">
          <h2 className="text-lg font-black text-slate-900">Bộ lọc</h2>
          <button
            type="button"
            className="text-sm font-bold text-blue-600 transition-colors hover:text-blue-700"
            onClick={() => onChange({ companies: '', time: '00:00-23:59', sort: 'time:asc', islimousine: '', fa: '', ta: '', rating: '' })}
          >
            Xóa hết
          </button>
        </div>

        <div className="space-y-6">
          <section className="space-y-3">
            <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Sắp xếp</h3>
            <select
              className="w-full rounded-xl border-2 border-slate-50 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none transition-all focus:border-blue-500 focus:bg-white"
              value={filters.sort}
              onChange={(event) => onChange({ sort: event.target.value })}
            >
              {SORT_OPTIONS.map((option) => (
                <option key={option.value} value={option.value}>
                  {option.label}
                </option>
              ))}
            </select>
          </section>

          <section className="space-y-3">
            <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Khung giờ đi</h3>
            <div className="grid grid-cols-2 gap-2">
              {TIME_OPTIONS.map((option) => (
                <button
                  key={option.value}
                  type="button"
                  onClick={() => onChange({ time: option.value })}
                  className={`rounded-xl border-2 py-2.5 text-[11px] font-bold transition-all ${
                    filters.time === option.value
                      ? 'border-blue-600 bg-blue-600 text-white shadow-lg shadow-blue-200'
                      : 'border-slate-50 bg-slate-50 text-slate-600 hover:border-slate-200'
                  }`}
                >
                  {option.label}
                </button>
              ))}
            </div>
          </section>

          <section className="space-y-3">
            <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Mức giá</h3>
            <select
              className="w-full rounded-xl border-2 border-slate-50 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none transition-all focus:border-blue-500 focus:bg-white"
              value={priceRange}
              onChange={(event) => onPriceRange(event.target.value)}
            >
              {PRICE_OPTIONS.map((option) => (
                <option key={option.value} value={option.value}>
                  {option.label}
                </option>
              ))}
            </select>
          </section>

          <section className="space-y-3">
            <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Đánh giá</h3>
            <div className="grid grid-cols-1 gap-2">
              {[4, 3, 2].map((r) => (
                <button
                  key={r}
                  type="button"
                  onClick={() => onChange({ rating: filters.rating === `${r}-5` ? '' : `${r}-5` })}
                  className={`flex items-center justify-between rounded-xl border-2 px-4 py-2.5 text-sm font-bold transition-all ${
                    filters.rating === `${r}-5`
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

          {companies.length > 0 && (
            <section className="space-y-3">
              <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Nhà xe</h3>
              <div className="max-h-60 space-y-1 overflow-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200">
                {companies
                  .filter((company) => Number(company.id) !== 11071)
                  .map((company) => (
                    <label
                      key={company.id}
                      className={`group flex cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2.5 transition-all ${
                        selectedCompanies.includes(String(company.id))
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
            </section>
          )}

          {statistics?.pickup_points?.length > 0 && (
            <section className="space-y-3">
              <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Điểm đón</h3>
              <div className="max-h-60 space-y-1 overflow-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200">
                {statistics.pickup_points.map((point, idx) => {
                  const selected = filters.fa.split(',').includes(point.district);
                  return (
                    <label
                      key={idx}
                      className={`group flex cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2.5 transition-all ${
                        selected ? 'bg-blue-50 text-blue-700' : 'hover:bg-slate-50 text-slate-600'
                      }`}
                    >
                      <span className="flex min-w-0 items-center gap-3">
                        <input
                          type="checkbox"
                          className="h-4 w-4 rounded-md border-slate-300 text-blue-600"
                          checked={selected}
                          onChange={() => {
                            const current = filters.fa ? filters.fa.split(',') : [];
                            const next = selected ? current.filter(x => x !== point.district) : [...current, point.district];
                            onChange({ fa: next.join(',') });
                          }}
                        />
                        <span className="truncate text-sm font-bold">{point.district}</span>
                      </span>
                      <span className="text-[10px] font-black opacity-40">{point.trip_count}</span>
                    </label>
                  );
                })}
              </div>
            </section>
          )}

          {statistics?.dropoff_points?.length > 0 && (
            <section className="space-y-3">
              <h3 className="text-xs font-black uppercase tracking-widest text-slate-400">Điểm trả</h3>
              <div className="max-h-60 space-y-1 overflow-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200">
                {statistics.dropoff_points.map((point, idx) => {
                  const selected = filters.ta.split(',').includes(point.district);
                  return (
                    <label
                      key={idx}
                      className={`group flex cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2.5 transition-all ${
                        selected ? 'bg-blue-50 text-blue-700' : 'hover:bg-slate-50 text-slate-600'
                      }`}
                    >
                      <span className="flex min-w-0 items-center gap-3">
                        <input
                          type="checkbox"
                          className="h-4 w-4 rounded-md border-slate-300 text-blue-600"
                          checked={selected}
                          onChange={() => {
                            const current = filters.ta ? filters.ta.split(',') : [];
                            const next = selected ? current.filter(x => x !== point.district) : [...current, point.district];
                            onChange({ ta: next.join(',') });
                          }}
                        />
                        <span className="truncate text-sm font-bold">{point.district}</span>
                      </span>
                      <span className="text-[10px] font-black opacity-40">{point.trip_count}</span>
                    </label>
                  );
                })}
              </div>
            </section>
          )}
        </div>
      </div>
    </aside>
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
      <div className="h-10 w-10 animate-spin rounded-full border-4 border-primary/20 border-t-primary"></div>
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
      <div className="flex flex-wrap border-b border-slate-50 bg-slate-50/50 p-2">
        {tabs.map((tab) => (
          <button
            key={tab.id}
            onClick={() => setActiveTab(tab.id)}
            className={`flex items-center gap-2 rounded-2xl px-6 py-3 text-xs font-black uppercase tracking-wider transition-all ${
              activeTab === tab.id
                ? 'bg-white text-primary shadow-sm'
                : 'text-slate-400 hover:text-slate-600'
            }`}
          >
            <i className={`fas ${tab.icon}`}></i> {tab.label}
          </button>
        ))}
      </div>

      <div className="p-8">
        {activeTab === 'images' && (
          <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
            {gallery.map((img, i) => (
              <div key={i} className="group relative aspect-video overflow-hidden rounded-2xl bg-slate-100">
                <img
                  src={img.medium || img.url}
                  className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                  alt={img.title || trip.company_name}
                />
              </div>
            ))}
          </div>
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
                          <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 shadow-inner">
                            {item.icon_url ? (
                              <img src={item.icon_url} className="h-6 w-6 object-contain" alt={item.name} />
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
                          className="flex items-center gap-2.5 rounded-full border border-slate-100 bg-slate-50/30 px-5 py-2.5 transition-all hover:bg-blue-50/30 hover:border-primary-light/20"
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
                  className="prose prose-slate max-w-none text-sm font-semibold leading-relaxed text-slate-600 bg-slate-50/50 rounded-3xl p-6 border border-slate-100
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
                        className="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-600 transition-all hover:bg-slate-100 disabled:pointer-events-none disabled:opacity-30"
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
                            className={`flex h-9 w-9 items-center justify-center rounded-xl text-xs font-black transition-all ${
                              reviewsPage === page
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
                        className="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-600 transition-all hover:bg-slate-100 disabled:pointer-events-none disabled:opacity-30"
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

  const partnerId = trip.partner?.partner_id || trip.partner_id || '';
  const partnerName = trip.partner?.partner_name || trip.partner_name || '';

  return (
    <li className={`dailyve-trip-card group relative flex flex-col overflow-hidden rounded-[18px] border bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-premium ${
      isBooking ? 'border-primary ring-1 ring-primary/20' : 'border-slate-100 hover:border-primary/70'
    }`}>
      {/* Important Notification Modal */}
      {showNoteModal && trip.important_notification?.content && createPortal(
        <div className="dailyve-important-modal fixed inset-0 z-[100] flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-slate-950/70 backdrop-blur-md transition-all duration-300" onClick={() => setShowNoteModal(false)}></div>
          <div className="relative w-full max-w-2xl transform overflow-hidden rounded-[2.5rem] bg-white border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.15)] animate-in zoom-in-95 duration-300 flex flex-col max-h-[85vh]">
            <div className="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-6 text-white shrink-0">
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/20 shadow-inner">
                    <i className="fas fa-exclamation-triangle"></i>
                  </div>
                  <h3 className="font-display text-xl font-black uppercase tracking-wider">Thông báo quan trọng</h3>
                </div>
                <button 
                  onClick={() => setShowNoteModal(false)}
                  className="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 transition-all hover:bg-white/20 active:scale-90 hover:rotate-90 duration-300"
                >
                  <i className="fas fa-times"></i>
                </button>
              </div>
            </div>
            
            <div className="p-8 md:p-10 overflow-y-auto pr-4 scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent flex-1">
              <div 
                className="prose prose-slate max-w-none text-slate-600 font-medium leading-relaxed
                  prose-p:mb-4 prose-p:last:mb-0
                  prose-ul:list-disc prose-ul:pl-5 prose-ul:space-y-2
                  prose-strong:text-slate-900 prose-strong:font-black"
                dangerouslySetInnerHTML={{ __html: trip.important_notification.content }}
              />
            </div>
            
            <div className="px-8 pb-8 pt-4 md:px-10 md:pb-10 shrink-0 border-t border-slate-50 bg-slate-50/30">
              <button
                onClick={() => setShowNoteModal(false)}
                className="w-full rounded-2xl bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 py-4 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-orange-500/20 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/30 active:scale-[0.98]"
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
          <div className="aspect-[4/3] overflow-hidden rounded-xl border border-slate-100 bg-slate-100 shadow-sm">
            {primaryImage ? (
              <img
                src={primaryImage}
                className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                alt={trip.company_name}
              />
            ) : (
              <div className="flex h-full w-full items-center justify-center text-3xl text-slate-300">
                <i className="fas fa-bus"></i>
              </div>
            )}
          </div>
          <div className="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-success px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-white shadow-md">
            <i className="fas fa-shield-alt mr-1"></i> Tin cậy
          </div>
        </div>

        {/* Info Content */}
        <div className="min-w-0 space-y-4">
          <div className="flex min-w-0 flex-col gap-3">
            <div className="min-w-0">
              <h3 className="truncate font-display text-[22px] font-semibold tracking-tight text-slate-900 sm:text-2xl lg:text-[26px]">{trip.company_name}</h3>
              <div className="mt-2 flex flex-wrap items-center gap-2 sm:gap-3">
                <span className="flex max-w-full items-center truncate rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold tracking-wide text-slate-600 sm:text-[11px]">
                  <i className="fas fa-bus-alt mr-2 text-primary"></i> {trip.vehicle_type}
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
                  <span className="font-display text-2xl font-semibold text-slate-950 sm:text-[28px]">{formatTime(trip.pickup_date)}</span>
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
                  <span className="font-display text-2xl font-semibold text-slate-950 sm:text-[28px]">{formatTime(trip.arrival_date)}</span>
                  <i className="fas fa-map-marker-alt text-xs text-primary"></i>
                </div>
                <div className="mt-1 truncate text-[11px] font-semibold tracking-wide text-slate-600 sm:text-xs">{trip.to_name}</div>
             </div>
          </div>
        </div>

        {/* Price & Action */}
        <div className="flex min-w-0 flex-col justify-between gap-4 border-t border-slate-100 pt-4 lg:h-full lg:border-l lg:border-t-0 lg:pl-6 lg:pt-0">
          <div className="text-left sm:text-right">
            {hasDiscount && (
              <div className="text-sm font-bold text-slate-300 line-through">
                {formatCurrency(trip.fare_original)}
              </div>
            )}
            <div className="font-display text-3xl font-bold tracking-tight text-primary-dark sm:text-4xl">
              {formatCurrency(trip.fare)}
            </div>
            <div className={`mt-1 text-xs font-semibold uppercase tracking-wide ${availableSeats <= 5 ? 'text-danger' : 'text-success'}`}>
              {availableSeats <= 5 ? `Chỉ còn ${trip.available_seat} ghế` : `Còn ${trip.available_seat} chỗ trống`}
            </div>
          </div>

          <div className="grid grid-cols-2 gap-2 lg:grid-cols-1">
            <button 
              onClick={() => setShowDetails(!showDetails)}
              className="flex min-h-12 items-center justify-center rounded-xl border-2 border-blue-100 bg-white px-3 text-xs font-black uppercase text-primary transition-all hover:border-primary hover:bg-blue-50 active:scale-95"
            >
              CHI TIẾT
            </button>
            <button 
              style={{ background: 'var(--grad-primary)' }}
              onClick={() => {
                setShowDetails(false);
                setIsBooking(true);
              }}
              className="flex min-h-12 items-center justify-center rounded-xl px-3 text-xs font-black uppercase text-white shadow-lg shadow-primary/20 transition-all hover:opacity-90 active:scale-95"
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
  const [queryString, setQueryString] = useState(window.location.search);
  const [filters, setFilters] = useState(getInitialFilters());
  
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
  const [loading, setLoading] = useState(false);
  const [loadingMore, setLoadingMore] = useState(false);
  const [error, setError] = useState('');
  const [priceRange, setPriceRange] = useState('all');

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

  return (
    <div className="dailyve-trip-list min-h-screen overflow-x-hidden bg-slate-50/50">
      <section className="dailyve-trip-hero relative overflow-visible bg-white pb-8 pt-6 md:pb-12 md:pt-10">
        <div className="relative mx-auto max-w-7xl px-3 sm:px-4">
          <div className="mb-6 text-center md:mb-8 md:text-left">
            <p className="inline-block rounded-full bg-blue-50 px-4 py-1.5 text-xs font-black uppercase tracking-widest text-blue-600">
               <i className="fas fa-route mr-2"></i> Hệ thống đặt vé Dailyve
            </p>
            <h1 className="mt-4 font-display text-2xl font-black leading-snug tracking-tight text-slate-900 sm:text-3xl md:text-5xl">
              Khám phá <span className="text-blue-600">Hành trình</span> của bạn
            </h1>
          </div>
          
          <div className="dailyve-trip-search-card glass-effect rounded-[18px] p-1.5 shadow-premium sm:p-2 md:rounded-[28px]">
            <SearchPanel
              filters={filters}
              onSubmit={handleNewSearch}
            />
          </div>
        </div>
      </section>

      <section className="mx-auto grid max-w-7xl gap-5 px-3 py-5 sm:px-4 lg:grid-cols-[280px_1fr]">
        <FilterPanel
          filters={filters}
          statistics={statistics}
          priceRange={priceRange}
          onPriceRange={setPriceRange}
          onChange={updateFilters}
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
