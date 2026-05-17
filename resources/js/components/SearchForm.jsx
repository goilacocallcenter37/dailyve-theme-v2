import React, { useEffect, useMemo, useState } from 'react';

const getTomorrow = () => {
  const date = new Date();
  date.setDate(date.getDate() + 1);
  return date.toISOString().slice(0, 10);
};

const products = [
  { id: 'bus', label: 'Xe khách', icon: 'fas fa-bus' },
  { id: 'train', label: 'Tàu hỏa', icon: 'fas fa-train' },
  { id: 'flight', label: 'Máy bay', icon: 'fas fa-plane' },
  { id: 'hotel', label: 'Khách sạn', icon: 'fas fa-hotel' },
];

const SearchForm = () => {
  const [locations, setLocations] = useState([]);
  const [activeProduct, setActiveProduct] = useState('bus');
  const [ticketCount, setTicketCount] = useState('1');

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
      passengers: ticketCount,
      service: activeProduct,
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
    <div className="dailyve-search">
      <div className="dailyve-search__tabs" role="tablist" aria-label="Chọn dịch vụ">
        {products.map((product) => (
          <button
            key={product.id}
            type="button"
            role="tab"
            aria-selected={activeProduct === product.id}
            className={activeProduct === product.id ? 'is-active' : ''}
            onClick={() => setActiveProduct(product.id)}
          >
            <i className={product.icon} aria-hidden="true"></i>
            {product.label}
          </button>
        ))}
      </div>

      <form onSubmit={handleSearch} className="dailyve-search__form">
        <div className="dailyve-search__locations-wrapper">
          <label className="dailyve-search__field">
            <span>Nơi đi</span>
            <i className="fas fa-map-marker-alt" aria-hidden="true"></i>
            <select value={from} onChange={(event) => setFrom(event.target.value)} required>
              <option value="">Nhập nơi đi</option>
              {locations.map((location) => (
                <option key={location.id} value={location.id}>
                  {location.name}
                </option>
              ))}
            </select>
            <i className="fas fa-chevron-down dailyve-search__chevron" aria-hidden="true"></i>
          </label>

          <button
            type="button"
            className="dailyve-search__swap"
            aria-label="Đổi điểm đi và điểm đến"
            onClick={() => {
              const temp = from;
              setFrom(to);
              setTo(temp);
            }}
          >
            <i className="fas fa-exchange-alt" aria-hidden="true"></i>
          </button>

          <label className="dailyve-search__field">
            <span>Nơi đến</span>
            <i className="fas fa-map-pin" aria-hidden="true"></i>
            <select value={to} onChange={(event) => setTo(event.target.value)} required>
              <option value="">Nhập nơi đến</option>
              {locations.map((location) => (
                <option key={location.id} value={location.id}>
                  {location.name}
                </option>
              ))}
            </select>
            <i className="fas fa-chevron-down dailyve-search__chevron" aria-hidden="true"></i>
          </label>
        </div>

        <label className="dailyve-search__field dailyve-search__field--date">
          <span>Ngày đi</span>
          <i className="fas fa-calendar-alt" aria-hidden="true"></i>
          <input
            type="date"
            value={date}
            min={new Date().toISOString().slice(0, 10)}
            onChange={(event) => setDate(event.target.value)}
            required
          />
        </label>

        <label className="dailyve-search__field dailyve-search__field--tickets">
          <span>Số vé</span>
          <i className="fas fa-user" aria-hidden="true"></i>
          <select value={ticketCount} onChange={(event) => setTicketCount(event.target.value)}>
            <option value="1">1 vé</option>
            <option value="2">2 vé</option>
            <option value="3">3 vé</option>
            <option value="4">4 vé</option>
          </select>
          <i className="fas fa-chevron-down dailyve-search__chevron" aria-hidden="true"></i>
        </label>

        <button type="submit" className="dailyve-search__submit">
          <i className="fas fa-search" aria-hidden="true"></i>
          Tìm vé
        </button>
      </form>

      <ul className="dailyve-search__benefits" aria-label="Lợi ích khi đặt vé">
        <li><i className="fas fa-ticket-alt" aria-hidden="true"></i>Đặt vé siêu nhanh</li>
        <li><i className="fas fa-headset" aria-hidden="true"></i>Hỗ trợ 24/7</li>
        <li><i className="fas fa-gift" aria-hidden="true"></i>Nhiều ưu đãi hấp dẫn</li>
        <li><i className="fas fa-shield-alt" aria-hidden="true"></i>Xuất vé điện tử tiện lợi</li>
      </ul>
    </div>
  );
};

export default SearchForm;
