import React, { useEffect, useMemo, useRef, useState } from 'react';
import {
  addMonths,
  formatDisplayDate,
  getLunarLabel,
  getToday,
  getTomorrow,
  isSameDay,
  parseISODate,
  toLocalISODate,
  VI_MONTHS,
  VI_WEEKDAYS,
} from '../utils/vietnameseCalendar';

export { getToday, getTomorrow, toLocalISODate } from '../utils/vietnameseCalendar';

const locationGroups = [
  { level: 1, label: 'Tỉnh / Thành phố' },
  { level: 2, label: 'Quận / Huyện' },
  { level: 3, label: 'Phường - Xã' },
  { level: 4, label: 'Điểm đón phổ biến' },
];

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
        className="border-none outline-none focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 bg-transparent p-0 w-full shadow-none rounded-none! placeholder:text-slate-600"
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
      {/* <i className="fas fa-chevron-down dailyve-search__chevron" aria-hidden="true"></i> */}

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
                    className={`dailyve-location-menu__option ${location.id === activeId ? 'is-active' : ''
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
      {required && <input className="dailyve-date-required" value={value || ''} onChange={() => { }} required tabIndex={-1} aria-hidden="true" />}
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
