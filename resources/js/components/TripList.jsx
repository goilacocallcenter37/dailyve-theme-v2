import React, { useEffect, useMemo, useState } from 'react';
import SeatSelection from './SeatSelection';

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
  const [date, setDate] = useState(filters.date);
  const [returnDate, setReturnDate] = useState(filters.returnDate || '');
  const [isRoundTrip, setIsRoundTrip] = useState(!!filters.returnDate);

  useEffect(() => {
    setFrom(filters.from);
    setTo(filters.to);
    setDate(filters.date);
    setReturnDate(filters.returnDate || '');
    setIsRoundTrip(!!filters.returnDate);
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
    <div className="flex flex-col gap-4">
      <div className="flex gap-2 px-2">
        <button
          type="button"
          onClick={() => setIsRoundTrip(false)}
          className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${
            !isRoundTrip ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
          }`}
        >
          Một chiều
        </button>
        <button
          type="button"
          onClick={() => setIsRoundTrip(true)}
          className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${
            isRoundTrip ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
          }`}
        >
          Khứ hồi
        </button>
      </div>

      <form
        className={`grid grid-cols-1 gap-4 p-2 ${
          isRoundTrip 
            ? 'lg:grid-cols-[1fr_auto_1fr_1fr_1fr_auto]' 
            : 'lg:grid-cols-[1fr_auto_1fr_1fr_auto]'
        }`}
        onSubmit={(event) => {
          event.preventDefault();
          if (isRoundTrip && !returnDate) {
            alert('Vui lòng chọn ngày về cho chuyến khứ hồi');
            return;
          }
          onSubmit({
            from,
            to,
            date,
            returnDate: isRoundTrip ? returnDate : '',
            nameFrom: getLocationName(from),
            nameTo: getLocationName(to),
          });
        }}
      >
        <div className="relative group">
          <div className="absolute left-4 top-1/2 -translate-y-1/2 text-primary transition-transform group-focus-within:scale-110">
            <i className="fas fa-map-marker-alt"></i>
          </div>
          <select
            className="h-16 w-full rounded-3xl border-2 border-transparent bg-slate-50/50 pl-11 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary-light/30"
            value={from}
            onChange={(event) => setFrom(event.target.value)}
            required
          >
            <option value="">Điểm đi</option>
            {locations.map((location) => (
              <option key={location.id} value={location.id}>
                {location.name}
              </option>
            ))}
          </select>
        </div>

        <div className="flex items-center justify-center">
          <button
            type="button"
            className="h-12 w-12 rounded-full bg-white text-primary shadow-lg border border-slate-100 transition-all hover:rotate-180 hover:bg-primary hover:text-white active:scale-90"
            onClick={() => {
              const temp = from;
              setFrom(to);
              setTo(temp);
            }}
            aria-label="Đổi chiều"
          >
            <i className="fas fa-exchange-alt"></i>
          </button>
        </div>

        <div className="relative group">
          <div className="absolute left-4 top-1/2 -translate-y-1/2 text-primary transition-transform group-focus-within:scale-110">
            <i className="fas fa-map-pin"></i>
          </div>
          <select
            className="h-16 w-full rounded-3xl border-2 border-transparent bg-slate-50/50 pl-11 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary-light/30"
            value={to}
            onChange={(event) => setTo(event.target.value)}
            required
          >
            <option value="">Điểm đến</option>
            {locations.map((location) => (
              <option key={location.id} value={location.id}>
                {location.name}
              </option>
            ))}
          </select>
        </div>

        <div className="relative group">
          <div className="absolute left-4 top-1/2 -translate-y-1/2 text-primary transition-transform group-focus-within:scale-110">
            <i className="fas fa-calendar-day"></i>
          </div>
          <div className="absolute left-11 top-2 text-[10px] font-bold text-primary uppercase">Ngày đi</div>
          <input
            className="h-16 w-full rounded-3xl border-2 border-transparent bg-slate-50/50 pl-11 pr-4 pt-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary-light/30"
            type="date"
            value={date}
            min={new Date().toISOString().slice(0, 10)}
            onChange={(event) => setDate(event.target.value)}
            required
          />
        </div>

        {isRoundTrip && (
          <div className="relative group animate-in fade-in slide-in-from-left-4 duration-300">
            <div className="absolute left-4 top-1/2 -translate-y-1/2 text-primary transition-transform group-focus-within:scale-110">
              <i className="fas fa-calendar-check"></i>
            </div>
            <div className="absolute left-11 top-2 text-[10px] font-bold text-primary uppercase">Ngày về</div>
            <input
              className="h-16 w-full rounded-3xl border-2 border-transparent bg-slate-50/50 pl-11 pr-4 pt-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary-light/30"
              type="date"
              value={returnDate}
              min={date}
              onChange={(event) => setReturnDate(event.target.value)}
              required={isRoundTrip}
            />
          </div>
        )}

        <button
          type="submit"
          className="h-16 rounded-3xl bg-primary px-10 text-base font-black text-white shadow-xl shadow-primary/20 transition-all hover:bg-primary-dark hover:shadow-primary/30 active:scale-95"
        >
          TÌM CHUYẾN
        </button>
      </form>
    </div>
  );
};

const TripSkeleton = () => (
  <div className="flex animate-pulse flex-col overflow-hidden rounded-3xl border border-slate-100 bg-white p-5 md:flex-row md:items-center md:gap-6">
    <div className="h-32 w-32 rounded-2xl bg-slate-100"></div>
    <div className="flex-1 space-y-4 py-2">
      <div className="h-6 w-1/3 rounded-md bg-slate-100"></div>
      <div className="flex gap-2">
        <div className="h-4 w-20 rounded bg-slate-100"></div>
        <div className="h-4 w-20 rounded bg-slate-100"></div>
      </div>
      <div className="h-10 w-full rounded-md bg-slate-50"></div>
    </div>
    <div className="mt-6 w-full space-y-3 md:mt-0 md:w-56 md:border-l md:border-slate-100 md:pl-6">
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
    <aside className="grid gap-6 lg:sticky lg:top-24 lg:self-start">
      <div className="overflow-hidden rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
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

  const tabs = [
    { id: 'images', label: 'Hình ảnh', icon: 'fa-images' },
    { id: 'utilities', label: 'Tiện ích', icon: 'fa-wifi' },
    { id: 'policy', label: 'Chính sách', icon: 'fa-file-contract' },
    { id: 'reviews', label: 'Đánh giá', icon: 'fa-star' },
  ];

  return (
    <div className="overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-sm">
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
                <img src={img.medium || img.url} className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" alt={img.title} />
              </div>
            ))}
          </div>
        )}

        {activeTab === 'utilities' && (
          <div className="grid grid-cols-2 gap-6 md:grid-cols-4">
            {trip.amenities?.map((item, i) => (
              <div key={i} className="flex items-center gap-4 rounded-2xl border border-slate-50 p-4 transition-all hover:border-primary-light/30 hover:bg-blue-50/30">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-primary">
                   <i className="fas fa-check-circle"></i>
                </div>
                <span className="text-sm font-bold text-slate-600">{item.label}</span>
              </div>
            ))}
          </div>
        )}

        {activeTab === 'policy' && (
          <div className="max-w-3xl space-y-4">
            <h4 className="font-display text-lg font-black text-slate-900">Chính sách nhà xe</h4>
            <div className="rounded-2xl bg-slate-50 p-6 text-sm font-medium leading-relaxed text-slate-600">
               {trip.policy || "Đang cập nhật chính sách từ nhà xe..."}
            </div>
          </div>
        )}

        {activeTab === 'reviews' && (
          <div className="space-y-6">
             <div className="flex items-center gap-6 rounded-3xl bg-blue-50 p-8">
                <div className="text-center">
                   <div className="font-display text-5xl font-black text-primary">{trip.ratings?.overall || 0}</div>
                   <div className="mt-1 text-[10px] font-black uppercase tracking-widest text-primary/60">Điểm đánh giá</div>
                </div>
                <div className="h-12 w-px bg-primary/10"></div>
                <div className="text-sm font-bold text-slate-600">
                   Dựa trên {trip.ratings?.comments || 0} nhận xét từ khách hàng đã đi chuyến này.
                </div>
             </div>
          </div>
        )}
      </div>
    </div>
  );
};

const TripCard = ({ trip, stepTicket, setStepTicket, filters, syncUrl }) => {
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
  
  const partnerId = trip.partner?.partner_id || trip.partner_id || '';
  const partnerName = trip.partner?.partner_name || trip.partner_name || '';

  return (
    <li className="group relative flex flex-col overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm transition-all duration-500 hover:border-primary-light hover:shadow-premium">
      {/* Important Notification Modal */}
      {showNoteModal && trip.important_notification?.content && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300" onClick={() => setShowNoteModal(false)}></div>
          <div className="relative w-full max-w-2xl transform overflow-hidden rounded-[2.5rem] bg-white shadow-2xl animate-in zoom-in-95 duration-300">
            <div className="bg-blue-600 px-8 py-6 text-white">
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/20">
                    <i className="fas fa-exclamation-triangle"></i>
                  </div>
                  <h3 className="font-display text-xl font-black uppercase tracking-tight">Thông báo quan trọng</h3>
                </div>
                <button 
                  onClick={() => setShowNoteModal(false)}
                  className="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 transition-colors hover:bg-white/20"
                >
                  <i className="fas fa-times"></i>
                </button>
              </div>
            </div>
            
            <div className="p-10">
              <div 
                className="prose prose-slate max-w-none text-base font-medium leading-relaxed text-slate-600"
                dangerouslySetInnerHTML={{ __html: trip.important_notification.content }}
              />
              
              <div className="mt-10">
                <button
                  onClick={() => setShowNoteModal(false)}
                  className="w-full rounded-2xl bg-slate-900 py-4 text-sm font-black uppercase tracking-widest text-white transition-all hover:bg-slate-800 active:scale-95"
                >
                  ĐÃ HIỂU & TIẾP TỤC
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
      <div className="flex flex-col p-6 md:flex-row md:items-center md:gap-8">
        {/* Logo & Confirm */}
        <div className="relative mb-6 shrink-0 md:mb-0">
          <div className="h-24 w-24 overflow-hidden rounded-3xl border border-slate-50 bg-slate-50 p-2 md:h-32 md:w-32">
            <img 
              src={trip.company_logo} 
              className="h-full w-full object-contain transition-transform duration-700 group-hover:scale-110" 
              alt={trip.company_name} 
            />
          </div>
          <div className="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-success px-4 py-1.5 text-[10px] font-black uppercase tracking-wider text-white shadow-md">
            <i className="fas fa-shield-alt mr-1"></i> Tin cậy
          </div>
        </div>

        {/* Info Content */}
        <div className="flex-1 space-y-6">
          <div className="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
              <h3 className="font-display text-2xl font-black tracking-tight text-slate-900 md:text-3xl">{trip.company_name}</h3>
              <div className="mt-2 flex flex-wrap items-center gap-4">
                <span className="flex items-center rounded-xl bg-slate-100 px-3 py-1.5 text-[11px] font-black uppercase tracking-wider text-slate-600">
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
              <div className="relative group/notify">
                <div className="flex items-center gap-2 cursor-pointer rounded-full bg-amber-50 px-4 py-2 border border-amber-100 transition-all hover:bg-amber-100">
                  <span className="relative flex h-2 w-2">
                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span className="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                  </span>
                  <span className="text-[11px] font-black uppercase tracking-wider text-amber-700">{trip.notification.label}</span>
                </div>
                
                {/* Tooltip content */}
                <div className="invisible group-hover/notify:visible absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-72 z-50 transition-all duration-300 opacity-0 group-hover/notify:opacity-100">
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

          <div className="relative flex items-center justify-between gap-6 py-4">
             <div className="absolute left-16 right-16 top-1/2 h-0.5 -translate-y-1/2 border-t-2 border-dashed border-slate-100"></div>
             
             <div className="relative z-10 bg-white pr-4 text-center">
                <div className="font-display text-2xl font-black text-slate-950">{formatTime(trip.pickup_date)}</div>
                <div className="mt-1 text-xs font-bold text-slate-400 uppercase tracking-widest">{trip.from_name}</div>
             </div>

             <div className="relative z-10 rounded-full bg-primary-light/20 px-4 py-1.5 text-[10px] font-black text-primary border border-primary-light/30">
                {routeDuration(trip.pickup_date, trip.arrival_date)}
             </div>

             <div className="relative z-10 bg-white pl-4 text-center">
                <div className="font-display text-2xl font-black text-slate-950">{formatTime(trip.arrival_date)}</div>
                <div className="mt-1 text-xs font-bold text-slate-400 uppercase tracking-widest">{trip.to_name}</div>
             </div>
          </div>
        </div>

        {/* Price & Action */}
        <div className="mt-8 shrink-0 space-y-4 border-t border-slate-50 pt-6 md:mt-0 md:w-64 md:border-l md:border-t-0 md:pl-8 md:pt-0">
          <div className="text-right">
            <div className="text-sm font-bold text-slate-300 line-through">
               {trip.fare_discount > 0 && formatCurrency(trip.fare_original)}
            </div>
            <div className="font-display text-4xl font-black tracking-tighter text-primary-dark">
              {formatCurrency(trip.fare)}
            </div>
            <div className={`mt-1 text-xs font-black uppercase tracking-widest ${Number(trip.available_seat) <= 5 ? 'text-danger' : 'text-success'}`}>
              {Number(trip.available_seat) <= 5 ? `Chỉ còn ${trip.available_seat} ghế` : `Còn ${trip.available_seat} chỗ trống`}
            </div>
          </div>

          <div className="grid grid-cols-2 gap-3 md:grid-cols-1">
            <button 
              onClick={() => setShowDetails(!showDetails)}
              className="flex items-center justify-center rounded-2xl border-2 border-slate-100 py-4 text-sm font-black text-slate-600 transition-all hover:bg-slate-50 active:scale-95"
            >
              CHI TIẾT
            </button>
            <button 
              style={{ background: 'var(--grad-primary)' }}
              onClick={() => setIsBooking(true)}
              className="flex items-center justify-center rounded-2xl py-4 text-sm font-black text-white shadow-xl shadow-primary/20 transition-all hover:opacity-90 active:scale-95"
            >
              CHỌN CHUYẾN
            </button>
          </div>
        </div>
      </div>

      {isBooking && (
        <div className="border-t-2 border-primary/10 bg-white">
          <div className="p-8 lg:p-10">
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
        <div className="border-t border-slate-50 bg-slate-50/30 p-8">
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
    <div className="min-h-screen bg-slate-50/50">
      <section className="relative overflow-hidden bg-white pb-16 pt-10 md:pt-14">
        {/* Decorative background elements */}
        <div className="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-blue-50/50 blur-3xl"></div>
        <div className="absolute -left-24 top-1/2 h-64 w-64 rounded-full bg-blue-100/30 blur-3xl"></div>

        <div className="relative mx-auto max-w-7xl px-4">
          <div className="mb-10 text-center md:text-left">
            <p className="inline-block rounded-full bg-blue-50 px-4 py-1.5 text-xs font-black uppercase tracking-widest text-blue-600">
               <i className="fas fa-route mr-2"></i> Hệ thống đặt vé Dailyve
            </p>
            <h1 className="mt-4 font-display text-4xl font-black tracking-tight text-slate-900 md:text-6xl">
              Khám phá <span className="text-blue-600">Hành trình</span> của bạn
            </h1>
          </div>
          
          <div className="glass-effect rounded-[2.5rem] p-2 shadow-premium">
            <SearchPanel
              filters={filters}
              onSubmit={handleNewSearch}
            />
          </div>
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
          <div className="mb-6 flex flex-col justify-between gap-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm md:flex-row md:items-center">
            <div>
              <div className="flex items-center gap-2">
                <h2 className="text-xl font-black text-slate-900">
                  {stepTicket === 0 ? 'Chiều đi: ' : 'Chiều về: '}
                  {filters.nameFrom && filters.nameTo ? (
                    stepTicket === 0 ? `${filters.nameFrom} → ${filters.nameTo}` : `${filters.nameTo} → ${filters.nameFrom}`
                  ) : (filters.from && filters.to ? (
                    stepTicket === 0 ? `${filters.from} → ${filters.to}` : `${filters.to} → ${filters.from}`
                  ) : 'Tìm chuyến xe')}
                </h2>
                {loading && <div className="h-2 w-2 animate-ping rounded-full bg-blue-500"></div>}
              </div>
              <p className="mt-1 text-sm font-bold text-slate-400">
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
            <div className="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white py-20 px-10 text-center">
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
                    syncUrl={syncUrl}
                  />
                ))}
              </div>

              {nextCursor && paging.hasMore && (
                <div className="mt-8 flex justify-center">
                  <button
                    type="button"
                    className="group flex items-center gap-3 rounded-2xl border-2 border-blue-100 bg-white px-8 py-4 text-sm font-black text-blue-600 shadow-sm transition-all hover:border-blue-500 hover:bg-blue-50 active:scale-95 disabled:cursor-wait disabled:opacity-60"
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
            <div className="flex flex-col items-center justify-center rounded-3xl border border-slate-100 bg-white py-20 px-10 text-center shadow-sm">
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
