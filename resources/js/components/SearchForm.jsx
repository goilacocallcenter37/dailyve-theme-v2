import React, { useEffect, useMemo, useState } from 'react';

const getTomorrow = () => {
  const date = new Date();
  date.setDate(date.getDate() + 1);
  return date.toISOString().slice(0, 10);
};

const SearchForm = () => {
  const [locations, setLocations] = useState([]);
  
  const [from, setFrom] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('from') || '';
  });
  const [to, setTo] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('to') || '';
  });
  const [date, setDate] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('date') || getTomorrow();
  });
  const [returnDate, setReturnDate] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('returnDate') || '';
  });
  const [isRoundTrip, setIsRoundTrip] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return !!params.get('returnDate');
  });

  useEffect(() => {
    fetch('/wp-json/api/v1/state-city-new')
      .then((res) => res.json())
      .then((res) => {
        if (res.success && Array.isArray(res.data)) {
          setLocations(
            res.data
              .filter((item) => item.name)
              .map((item) => ({
                id: String(item._id),
                name: item.nameWithType || item.name,
              })),
          );
        }
      })
      .catch((err) => console.error('Error fetching locations:', err));
  }, []);

  const locationMap = useMemo(() => {
    return locations.reduce((map, location) => {
      map[location.id] = location.name;
      return map;
    }, {});
  }, [locations]);

  const handleSearch = (event) => {
    event.preventDefault();

    if (isRoundTrip && !returnDate) {
      alert('Vui lòng chọn ngày về cho chuyến khứ hồi');
      return;
    }

    const params = new URLSearchParams({
      from,
      to,
      date,
    });

    if (isRoundTrip && returnDate) {
      params.set('returnDate', returnDate);
    }

    if (locationMap[from]) {
      params.set('nameFrom', locationMap[from]);
    }

    if (locationMap[to]) {
      params.set('nameTo', locationMap[to]);
    }

    window.location.href = `/dat-ve-truc-tuyen/?${params.toString()}`;
  };

  return (
    <div className="mx-auto max-w-6xl">
      {/* Search Type Toggle */}
      <div className="mb-4 flex gap-2">
        <button
          type="button"
          onClick={() => setIsRoundTrip(false)}
          className={`flex items-center gap-2 rounded-full px-6 py-2 text-sm font-bold transition-all ${
            !isRoundTrip
              ? 'bg-primary text-white shadow-lg shadow-primary/20'
              : 'bg-white/50 text-slate-600 hover:bg-white'
          }`}
        >
          <i className="fas fa-arrow-right"></i>
          Một chiều
        </button>
        <button
          type="button"
          onClick={() => setIsRoundTrip(true)}
          className={`flex items-center gap-2 rounded-full px-6 py-2 text-sm font-bold transition-all ${
            isRoundTrip
              ? 'bg-primary text-white shadow-lg shadow-primary/20'
              : 'bg-white/50 text-slate-600 hover:bg-white'
          }`}
        >
          <i className="fas fa-exchange-alt"></i>
          Khứ hồi
        </button>
      </div>

      <div className="glass-effect relative z-10 rounded-[2.5rem] p-2 shadow-premium border border-white/50">
        <form 
          onSubmit={handleSearch} 
          className={`grid grid-cols-1 gap-4 p-4 ${
            isRoundTrip 
              ? 'lg:grid-cols-[1fr_auto_1fr_1fr_1fr_auto]' 
              : 'lg:grid-cols-[1fr_auto_1fr_1fr_auto]'
          }`}
        >
          <div className="relative group">
            <div className="absolute left-4 top-1/2 -translate-y-1/2 text-primary transition-transform group-focus-within:scale-110">
              <i className="fas fa-map-marker-alt"></i>
            </div>
            <select
              value={from}
              onChange={(event) => setFrom(event.target.value)}
              className="h-16 w-full rounded-3xl border-2 border-transparent bg-slate-50/80 pl-11 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary-light/30"
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
            >
              <i className="fas fa-exchange-alt"></i>
            </button>
          </div>

          <div className="relative group">
            <div className="absolute left-4 top-1/2 -translate-y-1/2 text-primary transition-transform group-focus-within:scale-110">
              <i className="fas fa-map-pin"></i>
            </div>
            <select
              value={to}
              onChange={(event) => setTo(event.target.value)}
              className="h-16 w-full rounded-3xl border-2 border-transparent bg-slate-50/80 pl-11 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary-light/30"
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
              type="date"
              value={date}
              min={new Date().toISOString().slice(0, 10)}
              onChange={(event) => setDate(event.target.value)}
              className="h-16 w-full rounded-3xl border-2 border-transparent bg-slate-50/80 pl-11 pr-4 pt-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary-light/30"
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
                type="date"
                value={returnDate}
                min={date}
                onChange={(event) => setReturnDate(event.target.value)}
                className="h-16 w-full rounded-3xl border-2 border-transparent bg-slate-50/80 pl-11 pr-4 pt-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary-light/30"
                required={isRoundTrip}
              />
            </div>
          )}

          <button
            type="submit"
            className="h-16 rounded-3xl bg-primary px-10 text-base font-black text-white shadow-xl shadow-primary/20 transition-all hover:bg-primary-dark hover:shadow-primary/30 active:scale-95 lg:w-full"
          >
            TÌM CHUYẾN
          </button>
        </form>
      </div>
    </div>
  );
};

export default SearchForm;
