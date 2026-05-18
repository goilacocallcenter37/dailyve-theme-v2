import React, { useEffect, useMemo, useRef, useState } from 'react';

const VI_WEEKDAYS = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
const VI_MONTHS = [
  'Tháng 1',
  'Tháng 2',
  'Tháng 3',
  'Tháng 4',
  'Tháng 5',
  'Tháng 6',
  'Tháng 7',
  'Tháng 8',
  'Tháng 9',
  'Tháng 10',
  'Tháng 11',
  'Tháng 12',
];

const locationGroups = [
  { level: 1, label: 'Tỉnh / Thành phố' },
  { level: 2, label: 'Quận / Huyện' },
  { level: 3, label: 'Điểm đón phổ biến' },
];

const padNumber = (value) => String(value).padStart(2, '0');

export const toLocalISODate = (date) =>
  `${date.getFullYear()}-${padNumber(date.getMonth() + 1)}-${padNumber(date.getDate())}`;

export const getToday = () => toLocalISODate(new Date());

export const getTomorrow = () => {
  const date = new Date();
  date.setDate(date.getDate() + 1);
  return toLocalISODate(date);
};

export const normalizeText = (value) =>
  String(value || '')
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/đ/g, 'd')
    .replace(/\s+/g, ' ')
    .trim();

export const mapLocationPayload = (payload = []) => {
  const rawLocations = payload.filter((item) => item._id && item.name);
  const nameById = rawLocations.reduce((map, item) => {
    map[String(item._id)] = item.nameWithType || item.name;
    return map;
  }, {});

  return rawLocations.map((item) => {
    const id = String(item._id);
    const name = item.nameWithType || item.name;
    const shortName = item.name || name;
    const parentId = item.parent ? String(item.parent) : '';
    const parentName = parentId ? nameById[parentId] || '' : '';

    return {
      id,
      name,
      shortName,
      parentName,
      level: Number(item.level) || 1,
      searchText: normalizeText([
        name,
        shortName,
        parentName,
        item.normalizedName,
        item.slug,
      ].join(' ')),
    };
  });
};

export const buildLocationMap = (locations) =>
  locations.reduce((map, location) => {
    map[location.id] = location;
    return map;
  }, {});

export const fetchLocationsWithCache = (callback) => {
  const cachedData = localStorage.getItem('dailyve_cached_locations');
  const cachedTime = localStorage.getItem('dailyve_cached_locations_time');
  const CACHE_DURATION = 24 * 60 * 60 * 1000; // 24 hours

  let hasValidCache = false;

  if (cachedData && cachedTime) {
    try {
      const parsed = JSON.parse(cachedData);
      if (Array.isArray(parsed) && parsed.length > 0) {
        callback(parsed);
        if (Date.now() - Number(cachedTime) < CACHE_DURATION) {
          hasValidCache = true;
        }
      }
    } catch (e) {
      console.error('Failed to parse cached locations:', e);
    }
  }

  if (!hasValidCache) {
    fetch('/wp-json/api/v1/state-city-new')
      .then((res) => res.json())
      .then((res) => {
        if (res.success && Array.isArray(res.data)) {
          const mapped = mapLocationPayload(res.data);
          callback(mapped);
          try {
            localStorage.setItem('dailyve_cached_locations', JSON.stringify(mapped));
            localStorage.setItem('dailyve_cached_locations_time', String(Date.now()));
          } catch (e) {
            console.error('Failed to save locations cache:', e);
          }
        }
      })
      .catch((err) => console.error('Error fetching locations:', err));
  }
};

export const resolveLocationInput = (locations, locationMap, selectedId, query) => {
  if (selectedId && locationMap[selectedId]) {
    return locationMap[selectedId];
  }

  const normalizedQuery = normalizeText(query);
  if (!normalizedQuery) return null;

  return (
    locations.find(
      (location) =>
        normalizeText(location.name) === normalizedQuery ||
        normalizeText(location.shortName) === normalizedQuery,
    ) || null
  );
};

const parseISODate = (value) => {
  if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) return null;
  const [year, month, day] = value.split('-').map(Number);
  return new Date(year, month - 1, day);
};

const formatDisplayDate = (value) => {
  const date = parseISODate(value);
  if (!date) return '';
  return `${padNumber(date.getDate())}/${padNumber(date.getMonth() + 1)}/${date.getFullYear()}`;
};

const addMonths = (date, amount) => new Date(date.getFullYear(), date.getMonth() + amount, 1);

const isSameDay = (a, b) =>
  a &&
  b &&
  a.getFullYear() === b.getFullYear() &&
  a.getMonth() === b.getMonth() &&
  a.getDate() === b.getDate();

const jdFromDate = (day, month, year) => {
  const a = Math.floor((14 - month) / 12);
  const y = year + 4800 - a;
  const m = month + 12 * a - 3;
  let jd = day + Math.floor((153 * m + 2) / 5) + 365 * y + Math.floor(y / 4) - Math.floor(y / 100) + Math.floor(y / 400) - 32045;

  if (jd < 2299161) {
    jd = day + Math.floor((153 * m + 2) / 5) + 365 * y + Math.floor(y / 4) - 32083;
  }

  return jd;
};

const newMoon = (k) => {
  const t = k / 1236.85;
  const t2 = t * t;
  const t3 = t2 * t;
  const dr = Math.PI / 180;
  let jd = 2415020.75933 + 29.53058868 * k + 0.0001178 * t2 - 0.000000155 * t3;
  jd += 0.00033 * Math.sin((166.56 + 132.87 * t - 0.009173 * t2) * dr);
  const m = 359.2242 + 29.10535608 * k - 0.0000333 * t2 - 0.00000347 * t3;
  const mpr = 306.0253 + 385.81691806 * k + 0.0107306 * t2 + 0.00001236 * t3;
  const f = 21.2964 + 390.67050646 * k - 0.0016528 * t2 - 0.00000239 * t3;
  let c1 = (0.1734 - 0.000393 * t) * Math.sin(m * dr) + 0.0021 * Math.sin(2 * dr * m);
  c1 -= 0.4068 * Math.sin(mpr * dr) + 0.0161 * Math.sin(2 * dr * mpr);
  c1 -= 0.0004 * Math.sin(3 * dr * mpr);
  c1 += 0.0104 * Math.sin(2 * dr * f) - 0.0051 * Math.sin((m + mpr) * dr);
  c1 -= 0.0074 * Math.sin((m - mpr) * dr) + 0.0004 * Math.sin((2 * f + m) * dr);
  c1 -= 0.0004 * Math.sin((2 * f - m) * dr) - 0.0006 * Math.sin((2 * f + mpr) * dr);
  c1 += 0.0010 * Math.sin((2 * f - mpr) * dr) + 0.0005 * Math.sin((2 * mpr + m) * dr);
  const deltaT = t < -11 ? 0.001 + 0.000839 * t + 0.0002261 * t2 - 0.00000845 * t3 - 0.000000081 * t * t3 : -0.000278 + 0.000265 * t + 0.000262 * t2;
  return jd + c1 - deltaT;
};

const sunLongitude = (jdn) => {
  const t = (jdn - 2451545.0) / 36525;
  const t2 = t * t;
  const dr = Math.PI / 180;
  const m = 357.52910 + 35999.05030 * t - 0.0001559 * t2 - 0.00000048 * t * t2;
  const l0 = 280.46645 + 36000.76983 * t + 0.0003032 * t2;
  let dl = (1.914600 - 0.004817 * t - 0.000014 * t2) * Math.sin(dr * m);
  dl += (0.019993 - 0.000101 * t) * Math.sin(2 * dr * m) + 0.000290 * Math.sin(3 * dr * m);
  let l = (l0 + dl) * dr;
  l -= Math.PI * 2 * Math.floor(l / (Math.PI * 2));
  return l;
};

const getNewMoonDay = (k, timeZone) => Math.floor(newMoon(k) + 0.5 + timeZone / 24);
const getSunLongitude = (dayNumber, timeZone) => Math.floor((sunLongitude(dayNumber - 0.5 - timeZone / 24) / Math.PI) * 6);

const getLunarMonth11 = (year, timeZone) => {
  const off = jdFromDate(31, 12, year) - 2415021;
  const k = Math.floor(off / 29.530588853);
  let nm = getNewMoonDay(k, timeZone);
  const sunLong = getSunLongitude(nm, timeZone);
  if (sunLong >= 9) {
    nm = getNewMoonDay(k - 1, timeZone);
  }
  return nm;
};

const getLeapMonthOffset = (a11, timeZone) => {
  const k = Math.floor((a11 - 2415021.076998695) / 29.530588853 + 0.5);
  let last = 0;
  let i = 1;
  let arc = getSunLongitude(getNewMoonDay(k + i, timeZone), timeZone);

  do {
    last = arc;
    i += 1;
    arc = getSunLongitude(getNewMoonDay(k + i, timeZone), timeZone);
  } while (arc !== last && i < 14);

  return i - 1;
};

const solarToLunar = (date, timeZone = 7) => {
  const dayNumber = jdFromDate(date.getDate(), date.getMonth() + 1, date.getFullYear());
  const k = Math.floor((dayNumber - 2415021.076998695) / 29.530588853);
  let monthStart = getNewMoonDay(k + 1, timeZone);

  if (monthStart > dayNumber) {
    monthStart = getNewMoonDay(k, timeZone);
  }

  let a11 = getLunarMonth11(date.getFullYear(), timeZone);
  let b11 = a11;
  let lunarYear;

  if (a11 >= monthStart) {
    lunarYear = date.getFullYear();
    a11 = getLunarMonth11(date.getFullYear() - 1, timeZone);
  } else {
    lunarYear = date.getFullYear() + 1;
    b11 = getLunarMonth11(date.getFullYear() + 1, timeZone);
  }

  const lunarDay = dayNumber - monthStart + 1;
  const diff = Math.floor((monthStart - a11) / 29);
  let lunarLeap = false;
  let lunarMonth = diff + 11;

  if (b11 - a11 > 365) {
    const leapMonthDiff = getLeapMonthOffset(a11, timeZone);
    if (diff >= leapMonthDiff) {
      lunarMonth = diff + 10;
      if (diff === leapMonthDiff) {
        lunarLeap = true;
      }
    }
  }

  if (lunarMonth > 12) {
    lunarMonth -= 12;
  }

  if (lunarMonth >= 11 && diff < 4) {
    lunarYear -= 1;
  }

  return { day: lunarDay, month: lunarMonth, year: lunarYear, leap: lunarLeap };
};

const getLunarLabel = (date) => {
  const lunar = solarToLunar(date);
  return lunar.day === 1
    ? `${lunar.day}/${lunar.month}${lunar.leap ? 'N' : ''}`
    : String(lunar.day);
};

export const LocationCombobox = ({
  fieldId,
  label,
  icon,
  placeholder,
  locations,
  value,
  inputValue,
  onValueChange,
  onInputValueChange,
}) => {
  const inputRef = useRef(null);
  const [isOpen, setIsOpen] = useState(false);
  const [activeId, setActiveId] = useState('');

  const selectedLocation = useMemo(
    () => locations.find((location) => location.id === value),
    [locations, value],
  );

  const groupedLocations = useMemo(() => {
    const query = normalizeText(inputValue);
    const filteredLocations = query
      ? locations.filter((location) => location.searchText.includes(query))
      : locations;

    return locationGroups
      .map((group) => ({
        ...group,
        items: filteredLocations.filter((location) => location.level === group.level),
      }))
      .filter((group) => group.items.length > 0);
  }, [inputValue, locations]);

  const flatLocations = useMemo(
    () => groupedLocations.flatMap((group) => group.items),
    [groupedLocations],
  );

  useEffect(() => {
    if (isOpen) {
      setActiveId(flatLocations[0]?.id || '');
    }
  }, [flatLocations, isOpen]);

  const selectLocation = (location) => {
    onValueChange(location.id);
    onInputValueChange(location.name);
    setActiveId(location.id);
    setIsOpen(false);
  };

  const moveActive = (direction) => {
    if (!flatLocations.length) return;

    const currentIndex = flatLocations.findIndex((location) => location.id === activeId);
    const nextIndex =
      currentIndex === -1
        ? 0
        : (currentIndex + direction + flatLocations.length) % flatLocations.length;

    setActiveId(flatLocations[nextIndex].id);
  };

  return (
    <div 
      className="dailyve-search__field dailyve-search__field--combo"
      onClick={() => inputRef.current?.focus()}
    >
      <label className="dailyve-search__label" htmlFor={fieldId}>{label}</label>
      <i className={icon} aria-hidden="true"></i>
      <input
        ref={inputRef}
        id={fieldId}
        type="text"
        value={inputValue}
        placeholder={placeholder}
        autoComplete="off"
        role="combobox"
        aria-autocomplete="list"
        aria-expanded={isOpen}
        aria-controls={`${fieldId}-listbox`}
        aria-activedescendant={activeId ? `${fieldId}-option-${activeId}` : undefined}
        onFocus={() => setIsOpen(true)}
        onBlur={() => {
          window.setTimeout(() => {
            setIsOpen(false);
            if (selectedLocation) {
              onInputValueChange(selectedLocation.name);
            }
          }, 120);
        }}
        onChange={(event) => {
          onInputValueChange(event.target.value);
          onValueChange('');
          setIsOpen(true);
        }}
        onKeyDown={(event) => {
          if (event.key === 'ArrowDown') {
            event.preventDefault();
            if (!isOpen) {
              setIsOpen(true);
              return;
            }
            moveActive(1);
          }

          if (event.key === 'ArrowUp') {
            event.preventDefault();
            moveActive(-1);
          }

          if (event.key === 'Enter' && isOpen && activeId) {
            const location = flatLocations.find((item) => item.id === activeId);
            if (location) {
              event.preventDefault();
              selectLocation(location);
            }
          }

          if (event.key === 'Escape') {
            setIsOpen(false);
          }
        }}
      />
      <i className="fas fa-chevron-down dailyve-search__chevron" aria-hidden="true"></i>

      {isOpen && (
        <div className="dailyve-location-menu" id={`${fieldId}-listbox`} role="listbox">
          {groupedLocations.length > 0 ? (
            groupedLocations.map((group) => (
              <div className="dailyve-location-menu__group" key={group.level}>
                <div className="dailyve-location-menu__heading">{group.label}</div>
                {group.items.map((location) => (
                  <button
                    key={location.id}
                    id={`${fieldId}-option-${location.id}`}
                    type="button"
                    role="option"
                    aria-selected={location.id === value}
                    className={`dailyve-location-menu__option ${
                      location.id === activeId ? 'is-active' : ''
                    } ${location.id === value ? 'is-selected' : ''}`}
                    onMouseDown={(event) => event.preventDefault()}
                    onClick={() => selectLocation(location)}
                  >
                    <span className="dailyve-location-menu__main">{location.name}</span>
                    {location.parentName && (
                      <span className="dailyve-location-menu__meta">{location.parentName}</span>
                    )}
                  </button>
                ))}
              </div>
            ))
          ) : (
            <div className="dailyve-location-menu__empty">Không tìm thấy địa điểm</div>
          )}
        </div>
      )}
    </div>
  );
};

export const VietnameseDatePicker = ({
  label,
  icon = 'fas fa-calendar-alt',
  value,
  min = getToday(),
  onChange,
  emptyText = 'Chọn ngày',
  clearable = false,
  className = '',
  required = false,
}) => {
  const wrapperRef = useRef(null);
  const [isOpen, setIsOpen] = useState(false);
  const [viewDate, setViewDate] = useState(() => parseISODate(value || min) || new Date());

  const selectedDate = parseISODate(value);
  const minDate = parseISODate(min);
  const today = new Date();

  useEffect(() => {
    const nextView = parseISODate(value || min);
    if (nextView) {
      setViewDate(new Date(nextView.getFullYear(), nextView.getMonth(), 1));
    }
  }, [min, value]);

  useEffect(() => {
    const handlePointerDown = (event) => {
      if (wrapperRef.current && !wrapperRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    };

    document.addEventListener('mousedown', handlePointerDown);
    return () => document.removeEventListener('mousedown', handlePointerDown);
  }, []);

  const days = useMemo(() => {
    const firstOfMonth = new Date(viewDate.getFullYear(), viewDate.getMonth(), 1);
    const startOffset = (firstOfMonth.getDay() + 6) % 7;
    const startDate = new Date(viewDate.getFullYear(), viewDate.getMonth(), 1 - startOffset);

    return Array.from({ length: 42 }, (_, index) => {
      const day = new Date(startDate);
      day.setDate(startDate.getDate() + index);
      return day;
    });
  }, [viewDate]);

  const selectDate = (date) => {
    const isoDate = toLocalISODate(date);
    if (min && isoDate < min) return;

    onChange(isoDate);
    setIsOpen(false);
  };

  return (
    <div
      ref={wrapperRef}
      className={`${className} dailyve-date-field ${value ? 'has-value' : ''}`}
      onClick={(event) => {
        if (event.target.closest('.dailyve-datepicker')) return;
        if (event.target.closest('.dailyve-search__clear-date')) return;
        setIsOpen((open) => !open);
      }}
    >
      <span>{label}</span>
      <i className={icon} aria-hidden="true"></i>
      <button
        type="button"
        className="dailyve-date-trigger"
        aria-label={label}
        aria-expanded={isOpen}
        aria-haspopup="dialog"
        >
        <strong>{value ? formatDisplayDate(value) : emptyText}</strong>
      </button>
      {required && <input className="dailyve-date-required" value={value || ''} onChange={() => {}} required tabIndex={-1} aria-hidden="true" />}
      {clearable && value && (
        <button
          type="button"
          className="dailyve-search__clear-date"
          aria-label={`Xóa ${label.toLowerCase()}`}
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            onChange('');
          }}
        >
          <i className="fas fa-times" aria-hidden="true"></i>
        </button>
      )}

      {isOpen && (
        <div className="dailyve-datepicker" role="dialog" aria-label={`Chọn ${label.toLowerCase()}`}>
          <div className="dailyve-datepicker__header">
            <button type="button" onClick={() => setViewDate(addMonths(viewDate, -1))} aria-label="Tháng trước">
              <i className="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <div>
              <strong>{VI_MONTHS[viewDate.getMonth()]}</strong>
              <span>{viewDate.getFullYear()}</span>
            </div>
            <button type="button" onClick={() => setViewDate(addMonths(viewDate, 1))} aria-label="Tháng sau">
              <i className="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
          </div>

          <div className="dailyve-datepicker__weekdays">
            {VI_WEEKDAYS.map((weekday) => (
              <span key={weekday}>{weekday}</span>
            ))}
          </div>

          <div className="dailyve-datepicker__grid">
            {days.map((day) => {
              const isoDate = toLocalISODate(day);
              const isOutside = day.getMonth() !== viewDate.getMonth();
              const isDisabled = minDate && isoDate < min;
              const isSelected = isSameDay(day, selectedDate);
              const isToday = isSameDay(day, today);

              return (
                <button
                  key={isoDate}
                  type="button"
                  disabled={isDisabled}
                  className={[
                    isOutside ? 'is-outside' : '',
                    isDisabled ? 'is-disabled' : '',
                    isSelected ? 'is-selected' : '',
                    isToday ? 'is-today' : '',
                  ].filter(Boolean).join(' ')}
                  onClick={() => selectDate(day)}
                >
                  <span>{day.getDate()}</span>
                  <small>{getLunarLabel(day)}</small>
                </button>
              );
            })}
          </div>

          <div className="dailyve-datepicker__footer">
            <span>Âm lịch Việt Nam</span>
            <button type="button" disabled={getToday() < min} onClick={() => selectDate(new Date())}>Hôm nay</button>
          </div>
        </div>
      )}
    </div>
  );
};
