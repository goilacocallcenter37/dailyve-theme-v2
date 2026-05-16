import React, { useEffect, useMemo, useState } from 'react';

const getTomorrow = () => {
  const date = new Date();
  date.setDate(date.getDate() + 1);
  return date.toISOString().slice(0, 10);
};

const SearchForm = () => {
  const [locations, setLocations] = useState([]);
  const [from, setFrom] = useState('');
  const [to, setTo] = useState('');
  const [date, setDate] = useState(getTomorrow());

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

    const params = new URLSearchParams({
      from,
      to,
      date,
    });

    if (locationMap[from]) {
      params.set('nameFrom', locationMap[from]);
    }

    if (locationMap[to]) {
      params.set('nameTo', locationMap[to]);
    }

    window.location.href = `/dat-ve-truc-tuyen/?${params.toString()}`;
  };

  return (
    <div className="relative z-10 mx-auto -mt-12 max-w-5xl rounded-lg border border-slate-100 bg-white p-5 shadow-xl">
      <form onSubmit={handleSearch} className="grid grid-cols-1 items-end gap-4 md:grid-cols-[1fr_1fr_180px_160px]">
        <label className="grid gap-2 text-sm font-semibold text-slate-700">
          Điểm đi
          <select
            value={from}
            onChange={(event) => setFrom(event.target.value)}
            className="h-12 w-full rounded-md border border-slate-200 bg-slate-50 px-3 text-base font-medium text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
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

        <label className="grid gap-2 text-sm font-semibold text-slate-700">
          Điểm đến
          <select
            value={to}
            onChange={(event) => setTo(event.target.value)}
            className="h-12 w-full rounded-md border border-slate-200 bg-slate-50 px-3 text-base font-medium text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
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

        <label className="grid gap-2 text-sm font-semibold text-slate-700">
          Ngày đi
          <input
            type="date"
            value={date}
            min={new Date().toISOString().slice(0, 10)}
            onChange={(event) => setDate(event.target.value)}
            className="h-12 w-full rounded-md border border-slate-200 bg-slate-50 px-3 text-base font-medium text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
            required
          />
        </label>

        <button
          type="submit"
          className="h-12 rounded-md bg-blue-600 px-6 text-base font-bold text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700"
        >
          Tìm vé ngay
        </button>
      </form>
    </div>
  );
};

export default SearchForm;
