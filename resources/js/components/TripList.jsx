import React, { useEffect, useMemo, useState } from 'react';

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

const getTomorrow = () => {
  const date = new Date();
  date.setDate(date.getDate() + 1);
  return date.toISOString().slice(0, 10);
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
    date: formatDateInput(params.get('date') || getTomorrow()),
    sort: params.get('sort') || 'time:asc',
    time: params.get('time') || '00:00-23:59',
    companies: params.get('companies') || '',
    islimousine: params.get('islimousine') || '',
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
  const [date, setDate] = useState(filters.date);

  useEffect(() => {
    setFrom(filters.from);
    setTo(filters.to);
    setDate(filters.date);
  }, [filters]);

  useEffect(() => {
    fetch('/wp-json/api/v1/state-city-new')
      .then((response) => response.json())
      .then((response) => {
        if (response.success && Array.isArray(response.data)) {
          setLocations(
            response.data
              .filter((item) => item.name)
              .map((item) => ({
                id: String(item._id),
                name: item.nameWithType || item.name,
              })),
          );
        }
      })
      .catch(() => setLocations([]));
  }, []);

  const getLocationName = (id) => locations.find((item) => item.id === String(id))?.name || '';

  return (
    <form
      className="grid grid-cols-1 gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm lg:grid-cols-[1fr_auto_1fr_180px_150px]"
      onSubmit={(event) => {
        event.preventDefault();
        onSubmit({
          from,
          to,
          date,
          nameFrom: getLocationName(from),
          nameTo: getLocationName(to),
        });
      }}
    >
      <label className="grid gap-1 text-sm font-semibold text-slate-700">
        Điểm đi
        <select
          className="h-12 rounded-md border border-slate-200 bg-slate-50 px-3 text-base font-medium text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
          value={from}
          onChange={(event) => setFrom(event.target.value)}
          required
        >
          <option value="">Chọn điểm đi</option>
          {locations.map((location) => (
            <option key={location.id} value={location.id}>
              {location.name}
            </option>
          ))}
        </select>
      </label>

      <button
        type="button"
        className="hidden h-12 w-12 self-end rounded-md border border-slate-200 bg-white text-lg font-bold text-slate-600 transition hover:border-blue-400 hover:text-blue-600 lg:block"
        onClick={() => {
          setFrom(to);
          setTo(from);
        }}
        aria-label="Đổi chiều"
      >
        ↔
      </button>

      <label className="grid gap-1 text-sm font-semibold text-slate-700">
        Điểm đến
        <select
          className="h-12 rounded-md border border-slate-200 bg-slate-50 px-3 text-base font-medium text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
          value={to}
          onChange={(event) => setTo(event.target.value)}
          required
        >
          <option value="">Chọn điểm đến</option>
          {locations.map((location) => (
            <option key={location.id} value={location.id}>
              {location.name}
            </option>
          ))}
        </select>
      </label>

      <label className="grid gap-1 text-sm font-semibold text-slate-700">
        Ngày đi
        <input
          className="h-12 rounded-md border border-slate-200 bg-slate-50 px-3 text-base font-medium text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
          type="date"
          value={date}
          min={new Date().toISOString().slice(0, 10)}
          onChange={(event) => setDate(event.target.value)}
          required
        />
      </label>

      <button
        type="submit"
        className="h-12 self-end rounded-md bg-blue-600 px-5 text-base font-bold text-white shadow-sm transition hover:bg-blue-700"
      >
        Tìm chuyến
      </button>
    </form>
  );
};

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
    <aside className="grid gap-4 lg:sticky lg:top-24 lg:self-start">
      <div className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div className="mb-3 flex items-center justify-between">
          <h2 className="text-base font-bold text-slate-950">Bộ lọc</h2>
          <button
            type="button"
            className="text-sm font-semibold text-blue-600 hover:text-blue-700"
            onClick={() => onChange({ companies: '', time: '00:00-23:59', sort: 'time:asc', islimousine: '' })}
          >
            Xóa lọc
          </button>
        </div>

        <label className="mb-4 grid gap-2 text-sm font-semibold text-slate-700">
          Sắp xếp
          <select
            className="h-11 rounded-md border border-slate-200 bg-slate-50 px-3 text-sm font-medium outline-none focus:border-blue-500 focus:bg-white"
            value={filters.sort}
            onChange={(event) => onChange({ sort: event.target.value })}
          >
            {SORT_OPTIONS.map((option) => (
              <option key={option.value} value={option.value}>
                {option.label}
              </option>
            ))}
          </select>
        </label>

        <label className="mb-4 grid gap-2 text-sm font-semibold text-slate-700">
          Giờ đi
          <select
            className="h-11 rounded-md border border-slate-200 bg-slate-50 px-3 text-sm font-medium outline-none focus:border-blue-500 focus:bg-white"
            value={filters.time}
            onChange={(event) => onChange({ time: event.target.value })}
          >
            {TIME_OPTIONS.map((option) => (
              <option key={option.value} value={option.value}>
                {option.label}
              </option>
            ))}
          </select>
        </label>

        <label className="mb-4 grid gap-2 text-sm font-semibold text-slate-700">
          Giá vé
          <select
            className="h-11 rounded-md border border-slate-200 bg-slate-50 px-3 text-sm font-medium outline-none focus:border-blue-500 focus:bg-white"
            value={priceRange}
            onChange={(event) => onPriceRange(event.target.value)}
          >
            {PRICE_OPTIONS.map((option) => (
              <option key={option.value} value={option.value}>
                {option.label}
              </option>
            ))}
          </select>
        </label>

        {vehicleTypes.length > 0 && (
          <label className="mb-4 grid gap-2 text-sm font-semibold text-slate-700">
            Loại xe
            <select
              className="h-11 rounded-md border border-slate-200 bg-slate-50 px-3 text-sm font-medium outline-none focus:border-blue-500 focus:bg-white"
              value={filters.islimousine}
              onChange={(event) => onChange({ islimousine: event.target.value })}
            >
              <option value="">Tất cả xe</option>
              {vehicleTypes.map((type) => (
                <option key={type.value} value={type.value === 'LIMOUSINE' ? '1' : '2'}>
                  {type.value === 'LIMOUSINE' ? 'Xe limousine' : 'Xe thường'} ({type.count})
                </option>
              ))}
            </select>
          </label>
        )}

        {companies.length > 0 && (
          <div className="grid gap-2">
            <div className="text-sm font-bold text-slate-800">Nhà xe</div>
            <div className="max-h-72 overflow-auto pr-1">
              {companies
                .filter((company) => Number(company.id) !== 11071)
                .map((company) => (
                  <label
                    key={company.id}
                    className="flex cursor-pointer items-center justify-between gap-3 rounded-md px-2 py-2 text-sm text-slate-700 hover:bg-slate-50"
                  >
                    <span className="flex min-w-0 items-center gap-2">
                      <input
                        type="checkbox"
                        className="h-4 w-4 rounded border-slate-300 text-blue-600"
                        checked={selectedCompanies.includes(String(company.id))}
                        onChange={() => toggleCompany(company.id)}
                      />
                      <span className="truncate">{company.name}</span>
                    </span>
                    <span className="text-xs font-semibold text-slate-400">{company.trip_count}</span>
                  </label>
                ))}
            </div>
          </div>
        )}
      </div>
    </aside>
  );
};

const DetailTabs = ({ trip, gallery }) => (
  <div className="online-booking-page__provider-list__details-tab" id={`detail-tab-${trip.trip_id}`}>
    <div className="provider-details">
      <ul className="provider-details__nav">
        <li data-tab={`images-tab-${trip.trip_id}`} className="active">
          Hình ảnh
        </li>
        <li data-tab={`convenience-tab-${trip.trip_id}`}>Tiện ích</li>
        <li data-tab={`ratings-tab-${trip.trip_id}`}>Đánh giá</li>
        <li data-tab={`pickup-dropoff-points-tab-${trip.trip_id}`}>Điểm đón, trả</li>
        <li data-tab={`policy-tab-${trip.trip_id}`}>Chính sách</li>
      </ul>
      <div className="provider-details__tabs width-tab">
        <div id={`images-tab-${trip.trip_id}`} className="provider-details__tab images-tab">
          <div className="provider-details__gallery">
            <div className="provider-details__gallery-main">
              {gallery.map((image, index) => (
                <div className="provider-details__gallery-main__item" key={`${image.url}-${index}`}>
                  <img src={image.url} width={image.width || 900} height={image.height || 600} alt={image.title || trip.company_name} />
                </div>
              ))}
            </div>
            <div className="provider-details__gallery-thumbnails">
              {gallery.map((image, index) => (
                <div className="provider-details__gallery-thumbnails__item" key={`${image.medium}-${index}`}>
                  <img src={image.medium || image.url} width="240" height="160" alt={image.title || trip.company_name} />
                </div>
              ))}
            </div>
          </div>
        </div>
        <div id={`convenience-tab-${trip.trip_id}`} className="provider-details__tab">
          <ul className="provider_details_convenience__list"></ul>
          <div className="provider_details_convenience__list_2"></div>
        </div>
        <div id={`pickup-dropoff-points-tab-${trip.trip_id}`} className="provider-details__tab">
          <div className="bus-type-title">Lưu ý</div>
          <div className="header-content">Các mốc thời gian đón, trả bên dưới là thời gian dự kiến.</div>
          <div className="flex flex-wrap accordion-sub-item__wrapper">
            <div className="accordion-sub-item">
              <div className="accordion-sub-item__title">Điểm đón</div>
              <ul className="accordion-sub-item__list pickup-point-list"></ul>
            </div>
            <div className="accordion-sub-item">
              <div className="accordion-sub-item__title">Điểm trả</div>
              <ul className="accordion-sub-item__list dropoff-point-list"></ul>
            </div>
          </div>
        </div>
        <div id={`policy-tab-${trip.trip_id}`} className="provider-details__tab">
          <p className="policy-title">
            <strong>Chính sách nhà xe</strong>
          </p>
          <div className="content-policy-container"></div>
        </div>
        <div id={`ratings-tab-${trip.trip_id}`} className="provider-details__tab">
          <div className="ratings-tab__average">
            <span className="ratings-tab__average__point">
              <i className="fas fa-star"></i> {trip.ratings?.overall || 0}
            </span>
            <span className="ratings-tab__average__total-ratings">{trip.ratings?.comments || 0} đánh giá</span>
          </div>
          <div className="rating-tab__cats" id={`list-rating-cats-${trip.trip_id}`}></div>
          <div className="rating-tab__comments-list" id={`comment-list-${trip.trip_id}`}></div>
          <div className="rating-tab__comments-list-pagination" id={`comment-pagination-${trip.trip_id}`}></div>
        </div>
      </div>
    </div>
  </div>
);

const TripCard = ({ trip }) => {
  const gallery =
    Array.isArray(trip.company_gallery) && trip.company_gallery.length > 0
      ? trip.company_gallery
      : [{ url: trip.company_logo, medium: trip.company_logo, title: trip.company_name }];
  const partnerId = trip.partner?.partner_id || trip.partner_id || '';
  const partnerName = trip.partner?.partner_name || trip.partner_name || '';

  return (
    <li className="online-booking-page__provider-list__item" id={`route-trip-${trip.trip_id}`}>
      {trip.notification?.label && (
        <div className="online-booking-page__provider-list__item_header_notify">
          <div className="header_notify-note">
            <div className="notify-tag">
              <span>Thông báo</span>
            </div>
            <div className="tooltip notify-link">
              {trip.notification.label}
              <span className="tooltiptext tooltip-top">{trip.notification.content}</span>
            </div>
          </div>
        </div>
      )}

      <div className="online-booking-page__provider-list__item__img">
        <img src={trip.company_logo} width="162" height="162" alt={trip.company_name} loading="lazy" decoding="async" />
        <div className="instant-confirm">
          <div>
            <i className="fas fa-check-square"></i> Xác nhận tức thì
          </div>
          <div className="point"></div>
        </div>
      </div>

      <div className="online-booking-page__provider-list__item__info">
        <div className="online-booking-page__provider-list__item__bus-name-info">
          <p className="online-booking-page__provider-list__item__title">{trip.company_name}</p>
          <button type="button" className="ant-btn bus-rating-button">
            <div className="bus-rating">
              <i className="fas fa-star"></i>
              <span>
                {trip.ratings?.overall || 0} ({trip.ratings?.comments || 0})
              </span>
            </div>
          </button>
        </div>
        <div className="online-booking-page__provider-list__item__bus-type-info">
          <p>{trip.vehicle_type}</p>
        </div>
        <div className="online-booking-page__provider-list__item__route-info">
          <div className="online-booking-page__provider-list__item__route-info__item">
            <span className="online-booking-page__provider-list__item__route-info__item-time">{formatTime(trip.pickup_date)} • </span>
            <span className="online-booking-page__provider-list__item__route-info__item-place">{trip.from_name}</span>
          </div>
          <div className="online-booking-page__provider-list__item__route-info__travel-time">
            {routeDuration(trip.pickup_date, trip.arrival_date)}
          </div>
          <div className="online-booking-page__provider-list__item__route-info__item online-booking-page__provider-list__item__route-info__item--ct">
            <span className="online-booking-page__provider-list__item__route-info__item-time">{formatTime(trip.arrival_date)} • </span>
            <span className="online-booking-page__provider-list__item__route-info__item-place">{trip.to_name}</span>
          </div>
        </div>
      </div>

      <div className="online-booking-page__provider-list__item__handle">
        <div className="online-booking-page__provider-list__item__price">
          <div className="fare">
            {trip.fare_max > trip.fare_original ? 'Từ ' : ''}
            {formatCurrency(trip.fare)}
          </div>
          {trip.fare_discount > 0 && trip.fare_original > 0 && (
            <div className="fareSmall">
              <div className="small">{formatCurrency(trip.fare_original)}</div>
            </div>
          )}
        </div>
        <div className="online-booking-page__provider-list__item__available-seat">
          <div className={`seat-available ${Number(trip.available_seat) <= 5 ? 'text-red' : ''}`}>
            Còn {trip.available_seat || 0} chỗ trống
          </div>
        </div>
        <div className="online-booking-page__provider-list__item__btns">
          <div
            data-companyid={trip.company_id}
            data-load="0"
            data-tripid={trip.trip_id}
            data-seat-template-id={trip.seat_template_id}
            data-partner-id={partnerId}
            data-partner-name={partnerName}
            data-departure-time={trip.departure_time || ''}
            data-departure-date={trip.departure_date || ''}
            data-pickup-date={trip.pickup_date || ''}
            data-way-id={trip.way_id || ''}
            data-booking-id={trip.booking_id || ''}
            data-fare={trip.fare || 0}
            data-to={trip.toId || trip.to_name || ''}
            data-from={trip.fromId || trip.from_name || ''}
            className="online-booking-page__provider-list__item__details-btn"
          >
            Thông tin chi tiết
          </div>

          <button
            className="online-booking-page__provider-list__item__price-btn"
            data-trip={trip.trip_id}
            data-to={trip.toId || trip.to_name || ''}
            data-from={trip.fromId || trip.from_name || ''}
            data-partner-id={partnerId}
            data-departure-time={trip.departure_time || ''}
            data-departure-date={trip.departure_date || ''}
            data-pickup-date={trip.pickup_date || ''}
            data-way-id={trip.way_id || ''}
            data-booking-id={trip.booking_id || ''}
            data-fare={trip.fare || 0}
            data-unchoosable={trip.unchoosable || 0}
            data-route-name={trip.route_name || ''}
            type="button"
          >
            Chọn chuyến
          </button>
        </div>
      </div>

      <div className="online-booking-page__provider-list__item__full-route">
        {trip.route_name && trip.departure_date && (
          <div className="notify-trip">
            <div className="full-trip">
              <span>*</span>Vé chặng thuộc chuyến {trip.route_name}
            </div>
          </div>
        )}
        <div style={{ width: '100%' }} id={`ticket-loading-${trip.trip_id}`}></div>
      </div>

      <div className="online-booking-page__provider-list__seats-info" id={`seats-info-conetnt-${trip.trip_id}`}></div>
      <DetailTabs trip={trip} gallery={gallery} />

      {trip.important_notification?.content && (
        <div className="notice-box">
          <h3 className="notice-box__title">{trip.important_notification.label}</h3>
          <div className="notice-box__desc">{trip.important_notification.content}</div>
        </div>
      )}
    </li>
  );
};

const TripList = () => {
  const [filters, setFilters] = useState(getInitialFilters);
  const [trips, setTrips] = useState([]);
  const [statistics, setStatistics] = useState({});
  const [paging, setPaging] = useState({});
  const [nextCursor, setNextCursor] = useState('');
  const [priceRange, setPriceRange] = useState('all');
  const [loading, setLoading] = useState(false);
  const [loadingMore, setLoadingMore] = useState(false);
  const [error, setError] = useState('');

  const queryString = useMemo(() => buildQuery(filters).toString(), [filters]);

  const syncUrl = (nextFilters) => {
    const params = buildQuery(nextFilters);
    window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);
  };

  const updateFilters = (patch) => {
    const next = { ...filters, ...patch };
    setFilters(next);
    syncUrl(next);
  };

  const fetchTrips = (append = false, cursor = '') => {
    if (!filters.from || !filters.to) return;

    const params = buildQuery(filters, cursor ? { cursor } : {});
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
    fetchTrips(false);
  }, [queryString]);

  const visibleTrips = useMemo(() => trips.filter((trip) => priceMatches(trip, priceRange)), [trips, priceRange]);
  const total = paging.totalItems ?? trips.length;
  const routeTitle =
    trips.length > 0 ? `${trips[0].from_name || 'Điểm đi'} đi ${trips[0].to_name || 'Điểm đến'}` : 'Tìm chuyến xe';

  return (
    <div className="bg-slate-50">
      <section className="border-b border-slate-200 bg-white">
        <div className="mx-auto max-w-7xl px-4 py-6 md:py-8">
          <div className="mb-5">
            <p className="text-sm font-semibold uppercase tracking-wide text-blue-600">Dailyve</p>
            <h1 className="mt-1 text-2xl font-extrabold text-slate-950 md:text-4xl">Kết quả tìm chuyến</h1>
          </div>
          <SearchPanel
            filters={filters}
            onSubmit={(payload) => {
              const next = {
                ...filters,
                from: payload.from,
                to: payload.to,
                date: payload.date,
                companies: '',
              };
              setFilters(next);

              const params = buildQuery(next);
              if (payload.nameFrom) params.set('nameFrom', payload.nameFrom);
              if (payload.nameTo) params.set('nameTo', payload.nameTo);
              window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);
            }}
          />
        </div>
      </section>

      <section className="mx-auto grid max-w-7xl gap-5 px-4 py-6 lg:grid-cols-[280px_1fr]">
        <FilterPanel
          filters={filters}
          statistics={statistics}
          priceRange={priceRange}
          onPriceRange={setPriceRange}
          onChange={updateFilters}
        />

        <main className="min-w-0">
          <div className="mb-4 flex flex-col justify-between gap-2 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
            <div>
              <h2 className="text-lg font-bold text-slate-950">{routeTitle}</h2>
              <p className="text-sm text-slate-500">
                {filters.date ? `Ngày ${filters.date}` : 'Chọn ngày đi'} · {loading ? 'Đang tải...' : `${total} chuyến phù hợp`}
              </p>
            </div>
            <div className="text-sm font-semibold text-slate-600">Giữ nguyên luồng chọn ghế hiện tại</div>
          </div>

          {!filters.from || !filters.to ? (
            <div className="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
              Chọn điểm đi, điểm đến và ngày khởi hành để xem chuyến xe.
            </div>
          ) : error ? (
            <div className="rounded-lg border border-red-200 bg-red-50 p-5 text-red-700">{error}</div>
          ) : loading ? (
            <div className="grid gap-4">
              {Array.from({ length: 4 }).map((_, index) => (
                <div key={index} className="h-44 animate-pulse rounded-lg border border-slate-200 bg-white"></div>
              ))}
            </div>
          ) : visibleTrips.length > 0 ? (
            <>
              <ul className="online-booking-page__provider-list" total={total}>
                {visibleTrips.map((trip) => (
                  <TripCard key={`${trip.trip_id}-${trip.pickup_date}`} trip={trip} />
                ))}
              </ul>

              {nextCursor && paging.hasMore && (
                <div className="mt-5 flex justify-center">
                  <button
                    type="button"
                    className="rounded-md border border-blue-200 bg-white px-5 py-3 font-bold text-blue-700 shadow-sm transition hover:border-blue-500 hover:bg-blue-50 disabled:cursor-wait disabled:opacity-60"
                    disabled={loadingMore}
                    onClick={() => fetchTrips(true, nextCursor)}
                  >
                    {loadingMore ? 'Đang tải...' : 'Xem thêm chuyến'}
                  </button>
                </div>
              )}
            </>
          ) : (
            <div className="rounded-lg border border-slate-200 bg-white p-8 text-center text-slate-600">
              Chưa tìm thấy chuyến phù hợp. Hãy thử đổi ngày đi hoặc bỏ bớt bộ lọc.
            </div>
          )}
        </main>
      </section>
    </div>
  );
};

export default TripList;
