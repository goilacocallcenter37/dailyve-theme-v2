import React, { useEffect, useMemo, useState } from 'react';
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

const products = [
  { id: 'bus', label: 'Xe khách', icon: 'fas fa-bus' },
  { id: 'train', label: 'Tàu hỏa', icon: 'fas fa-train' },
  { id: 'flight', label: 'Máy bay', icon: 'fas fa-plane' },
  { id: 'hotel', label: 'Khách sạn', icon: 'fas fa-hotel' },
];

const SearchForm = () => {
  const [locations, setLocations] = useState([]);
  const [activeProduct, setActiveProduct] = useState('bus');

  const [from, setFrom] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('from') || '';
  });
  const [to, setTo] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('to') || '';
  });
  const [fromQuery, setFromQuery] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('nameFrom') || '';
  });
  const [toQuery, setToQuery] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('nameTo') || '';
  });
  const [date, setDate] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('date') || getTomorrow();
  });
  const [returnDate, setReturnDate] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('returnDate') || '';
  });

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

  const handleSearch = (event) => {
    event.preventDefault();

    const fromLocation = resolveLocationInput(locations, locationMap, from, fromQuery);
    const toLocation = resolveLocationInput(locations, locationMap, to, toQuery);

    if (!fromLocation || !toLocation) {
      alert('Vui lòng chọn điểm đi và điểm đến trong danh sách.');
      return;
    }

    const params = new URLSearchParams({
      from: fromLocation.id,
      to: toLocation.id,
      date,
      service: activeProduct,
    });

    params.set('nameFrom', fromLocation.name);
    params.set('nameTo', toLocation.name);

    if (returnDate) {
      params.set('returnDate', returnDate);
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
          <LocationCombobox
            fieldId="dailyve-from"
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
            aria-label="Đổi điểm đi và điểm đến"
            onClick={() => {
              const temp = from;
              const tempQuery = fromQuery;
              setFrom(to);
              setFromQuery(toQuery);
              setTo(temp);
              setToQuery(tempQuery);
            }}
          >
            <i className="fas fa-exchange-alt" aria-hidden="true"></i>
          </button>

          <LocationCombobox
            fieldId="dailyve-to"
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
          icon="fas fa-calendar-alt"
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
          onChange={setReturnDate}
          emptyText="Chọn ngày về"
          clearable
          className="dailyve-search__field dailyve-search__field--return"
        />

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
