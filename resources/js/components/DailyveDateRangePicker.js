import {
  addMonths,
  compareISODates,
  formatDisplayDate,
  getLunarLabel,
  getToday,
  parseISODate,
  startOfMonth,
  toLocalISODate,
  VI_WEEKDAYS,
} from '../utils/vietnameseCalendar';

export const DATE_RANGE_SELECTED_EVENT = 'dailyve:date-range-selected';

const TRIGGER_SELECTOR = '[data-dailyve-date-range-trigger], .js-route-price-datepicker';

let pickerInstance = null;
let isDelegationReady = false;

const isISODate = (value) => /^\d{4}-\d{2}-\d{2}$/.test(String(value || ''));

const resolveBoolean = (value, fallback = false) => {
  if (value === undefined || value === null || value === '') return fallback;
  return ['1', 'true', 'yes', 'on'].includes(String(value).toLowerCase());
};

const resolveMinDate = (value) => {
  if (!value || value === 'today') return getToday();
  return isISODate(value) ? value : getToday();
};

const safeURL = (value) => {
  if (!value) return null;

  try {
    return new URL(value, window.location.origin);
  } catch {
    return null;
  }
};

const getTriggerConfig = (trigger) => {
  const url = trigger.dataset.dateRangeUrl || trigger.dataset.routePriceUrl || trigger.getAttribute('href') || '';
  const target = trigger.dataset.dateRangeTarget || trigger.getAttribute('target') || '';
  const dateParam = trigger.dataset.dateRangeDateParam || 'date';
  const returnDateParam = trigger.dataset.dateRangeReturnDateParam || 'returnDate';
  const parsedURL = safeURL(url);
  const minDate = resolveMinDate(trigger.dataset.dateRangeMin);
  const initialDeparture = trigger.dataset.dateRangeDeparture || parsedURL?.searchParams.get(dateParam) || '';
  const initialReturn = trigger.dataset.dateRangeReturn || parsedURL?.searchParams.get(returnDateParam) || '';

  return {
    url,
    target,
    mode: trigger.dataset.dateRangeMode || 'redirect',
    dateParam,
    returnDateParam,
    minDate,
    initialDeparture: isISODate(initialDeparture) ? initialDeparture : '',
    initialReturn: isISODate(initialReturn) ? initialReturn : '',
    isRoundTrip: resolveBoolean(trigger.dataset.dateRangeRoundtrip, !!initialReturn),
    service: trigger.dataset.dateRangeService || trigger.dataset.routeService || 'bus',
    fromName: trigger.dataset.dateRangeFromName || trigger.dataset.routeFromName || '',
    toName: trigger.dataset.dateRangeToName || trigger.dataset.routeToName || '',
  };
};

class DailyveDateRangePicker {
  constructor() {
    this.state = this.getDefaultState();
    this.root = this.createRoot();
    this.refs = this.getRefs();
    this.bindEvents();
  }

  getDefaultState() {
    const today = getToday();

    return {
      config: {},
      trigger: null,
      viewDate: parseISODate(today),
      minDate: today,
      departureDate: '',
      returnDate: '',
      isRoundTrip: false,
    };
  }

  createRoot() {
    const existing = document.querySelector('[data-dailyve-date-range-picker]');
    if (existing) return existing;

    const root = document.createElement('div');
    root.className = 'dailyve-date-range-picker';
    root.dataset.dailyveDateRangePicker = '';
    root.hidden = true;
    root.setAttribute('aria-hidden', 'true');
    root.innerHTML = `
      <div class="dailyve-date-range-picker__backdrop" data-dv-drp-close></div>
      <div class="dailyve-date-range-picker__dialog" role="dialog" aria-modal="true" aria-labelledby="dailyve-date-range-picker-title" tabindex="-1">
        <header class="dailyve-date-range-picker__header">
          <div>
            <h2 id="dailyve-date-range-picker-title">Chọn ngày đi</h2>
            <p data-dv-drp-route></p>
          </div>
          <button type="button" class="dailyve-date-range-picker__x" data-dv-drp-close aria-label="Đóng">
            <i class="fas fa-times" aria-hidden="true"></i>
          </button>
        </header>

        <div class="dailyve-date-range-picker__calendar">
          <button type="button" class="dailyve-date-range-picker__nav dailyve-date-range-picker__nav--prev" data-dv-drp-prev aria-label="Tháng trước">
            <i class="fas fa-chevron-left" aria-hidden="true"></i>
          </button>
          <div class="dailyve-date-range-picker__months" data-dv-drp-months></div>
          <button type="button" class="dailyve-date-range-picker__nav dailyve-date-range-picker__nav--next" data-dv-drp-next aria-label="Tháng sau">
            <i class="fas fa-chevron-right" aria-hidden="true"></i>
          </button>
        </div>

        <footer class="dailyve-date-range-picker__footer">
          <div class="dailyve-date-range-picker__summary">
            <div class="dailyve-date-range-picker__summary-item">
              <span>Ngày đi</span>
              <strong data-dv-drp-departure-label>Chưa chọn</strong>
            </div>
            <div class="dailyve-date-range-picker__summary-item" data-dv-drp-return-summary hidden>
              <span>Ngày về</span>
              <strong data-dv-drp-return-label>Chưa chọn</strong>
            </div>
          </div>

          <label class="dailyve-date-range-picker__switch">
            <input type="checkbox" data-dv-drp-roundtrip>
            <span aria-hidden="true"></span>
            <strong>Khứ hồi</strong>
          </label>
        </footer>

        <div class="dailyve-date-range-picker__actions">
          <button type="button" class="dailyve-date-range-picker__close" data-dv-drp-close>Đóng</button>
          <button type="button" class="dailyve-date-range-picker__confirm" data-dv-drp-confirm disabled>Xác nhận</button>
        </div>
      </div>
    `;

    document.body.appendChild(root);
    return root;
  }

  getRefs() {
    return {
      dialog: this.root.querySelector('.dailyve-date-range-picker__dialog'),
      title: this.root.querySelector('#dailyve-date-range-picker-title'),
      route: this.root.querySelector('[data-dv-drp-route]'),
      months: this.root.querySelector('[data-dv-drp-months]'),
      prev: this.root.querySelector('[data-dv-drp-prev]'),
      next: this.root.querySelector('[data-dv-drp-next]'),
      confirm: this.root.querySelector('[data-dv-drp-confirm]'),
      roundtrip: this.root.querySelector('[data-dv-drp-roundtrip]'),
      departureLabel: this.root.querySelector('[data-dv-drp-departure-label]'),
      returnLabel: this.root.querySelector('[data-dv-drp-return-label]'),
      returnSummary: this.root.querySelector('[data-dv-drp-return-summary]'),
    };
  }

  bindEvents() {
    this.root.querySelectorAll('[data-dv-drp-close]').forEach((button) => {
      button.addEventListener('click', () => this.close());
    });

    this.refs.prev.addEventListener('click', () => {
      this.state.viewDate = addMonths(this.state.viewDate, -1);
      this.render();
    });

    this.refs.next.addEventListener('click', () => {
      this.state.viewDate = addMonths(this.state.viewDate, 1);
      this.render();
    });

    this.refs.roundtrip.addEventListener('change', () => {
      this.state.isRoundTrip = this.refs.roundtrip.checked;
      if (!this.state.isRoundTrip) {
        this.state.returnDate = '';
      } else if (this.state.returnDate && this.state.departureDate && compareISODates(this.state.returnDate, this.state.departureDate) < 0) {
        this.state.returnDate = '';
      }
      this.render();
    });

    this.refs.months.addEventListener('click', (event) => {
      const dayButton = event.target.closest('[data-dv-drp-day]');
      if (!dayButton || dayButton.disabled) return;
      this.selectDate(dayButton.dataset.dvDrpDay);
    });

    this.refs.confirm.addEventListener('click', () => {
      if (!this.refs.confirm.disabled) {
        this.confirm();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && this.isOpen()) {
        this.close();
      }
    });
  }

  open(trigger, options = {}) {
    const config = { ...getTriggerConfig(trigger), ...options };
    const minDate = resolveMinDate(config.minDate);
    const departureDate = isISODate(config.initialDeparture) && compareISODates(config.initialDeparture, minDate) >= 0
      ? config.initialDeparture
      : '';
    const returnDate = isISODate(config.initialReturn) && departureDate && compareISODates(config.initialReturn, departureDate) >= 0
      ? config.initialReturn
      : '';
    const viewBase = parseISODate(departureDate || minDate) || new Date();

    this.state = {
      config,
      trigger,
      viewDate: startOfMonth(viewBase),
      minDate,
      departureDate,
      returnDate,
      isRoundTrip: Boolean(config.isRoundTrip || returnDate),
    };

    this.refs.roundtrip.checked = this.state.isRoundTrip;
    this.render();

    this.root.hidden = false;
    this.root.setAttribute('aria-hidden', 'false');
    document.body.classList.add('dailyve-date-range-picker-open');

    window.requestAnimationFrame(() => {
      this.root.classList.add('is-open');
      this.refs.dialog.focus();
    });
  }

  close() {
    this.root.classList.remove('is-open');
    this.root.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('dailyve-date-range-picker-open');

    window.setTimeout(() => {
      if (!this.root.classList.contains('is-open')) {
        this.root.hidden = true;
      }
    }, 160);

    if (this.state.trigger && typeof this.state.trigger.focus === 'function') {
      this.state.trigger.focus();
    }
  }

  isOpen() {
    return this.root.classList.contains('is-open');
  }

  selectDate(isoDate) {
    if (!isISODate(isoDate) || compareISODates(isoDate, this.state.minDate) < 0) return;

    if (!this.state.isRoundTrip) {
      this.state.departureDate = isoDate;
      this.state.returnDate = '';
      this.render();
      return;
    }

    if (
      !this.state.departureDate ||
      this.state.returnDate ||
      compareISODates(isoDate, this.state.departureDate) < 0
    ) {
      this.state.departureDate = isoDate;
      this.state.returnDate = '';
    } else {
      this.state.returnDate = isoDate;
    }

    this.render();
  }

  render() {
    this.renderHeader();
    this.renderMonths();
    this.renderSummary();
  }

  renderHeader() {
    const { fromName, toName } = this.state.config;
    const hasRoute = fromName && toName;

    this.refs.title.textContent = this.state.isRoundTrip ? 'Chọn ngày đi và ngày về' : 'Chọn ngày đi';
    this.refs.route.textContent = hasRoute ? `${fromName} → ${toName}` : 'Lịch dương kèm âm lịch Việt Nam';
  }

  renderMonths() {
    const fragment = document.createDocumentFragment();

    for (let index = 0; index < 2; index += 1) {
      fragment.appendChild(this.createMonth(addMonths(this.state.viewDate, index)));
    }

    this.refs.months.replaceChildren(fragment);
  }

  createMonth(monthDate) {
    const month = document.createElement('section');
    month.className = 'dailyve-date-range-picker__month';

    const title = document.createElement('h3');
    title.textContent = `Tháng ${monthDate.getMonth() + 1}, ${monthDate.getFullYear()}`;
    month.appendChild(title);

    const weekdayGrid = document.createElement('div');
    weekdayGrid.className = 'dailyve-date-range-picker__weekdays';
    VI_WEEKDAYS.forEach((weekday, index) => {
      const item = document.createElement('span');
      item.textContent = weekday;
      if (index >= 5) item.className = 'is-weekend';
      weekdayGrid.appendChild(item);
    });
    month.appendChild(weekdayGrid);

    const dayGrid = document.createElement('div');
    dayGrid.className = 'dailyve-date-range-picker__grid';
    const firstOfMonth = new Date(monthDate.getFullYear(), monthDate.getMonth(), 1);
    const startOffset = (firstOfMonth.getDay() + 6) % 7;
    const startDate = new Date(monthDate.getFullYear(), monthDate.getMonth(), 1 - startOffset);

    for (let index = 0; index < 42; index += 1) {
      const day = new Date(startDate);
      day.setDate(startDate.getDate() + index);
      dayGrid.appendChild(this.createDayButton(day, monthDate));
    }

    month.appendChild(dayGrid);
    return month;
  }

  createDayButton(day, monthDate) {
    const isoDate = toLocalISODate(day);
    const lunarLabel = getLunarLabel(day);
    const isDisabled = compareISODates(isoDate, this.state.minDate) < 0;
    const isDeparture = isoDate === this.state.departureDate;
    const isReturn = isoDate === this.state.returnDate;
    const isInRange = this.state.departureDate &&
      this.state.returnDate &&
      compareISODates(isoDate, this.state.departureDate) > 0 &&
      compareISODates(isoDate, this.state.returnDate) < 0;
    const classes = ['dailyve-date-range-picker__day'];

    if (day.getMonth() !== monthDate.getMonth()) classes.push('is-outside');
    if (isDisabled) classes.push('is-disabled');
    if (isoDate === getToday()) classes.push('is-today');
    if (isDeparture) classes.push('is-selected', 'is-departure');
    if (isReturn) classes.push('is-selected', 'is-return');
    if (isInRange) classes.push('is-in-range');
    if (day.getDay() === 0 || day.getDay() === 6) classes.push('is-weekend');
    if (lunarLabel.includes('/')) classes.push('is-lunar-month');

    const button = document.createElement('button');
    button.type = 'button';
    button.className = classes.join(' ');
    button.dataset.dvDrpDay = isoDate;
    button.disabled = isDisabled;
    button.setAttribute('aria-label', `${formatDisplayDate(isoDate)}, âm lịch ${lunarLabel}`);
    button.setAttribute('aria-pressed', isDeparture || isReturn ? 'true' : 'false');

    const solar = document.createElement('span');
    solar.textContent = String(day.getDate());
    const lunar = document.createElement('small');
    lunar.textContent = lunarLabel;
    button.appendChild(solar);
    button.appendChild(lunar);

    return button;
  }

  renderSummary() {
    const { departureDate, returnDate, isRoundTrip } = this.state;

    this.refs.departureLabel.textContent = departureDate ? formatDisplayDate(departureDate) : 'Chưa chọn';
    this.refs.returnLabel.textContent = returnDate ? formatDisplayDate(returnDate) : 'Chưa chọn';
    this.refs.departureLabel.classList.toggle('has-value', Boolean(departureDate));
    this.refs.returnLabel.classList.toggle('has-value', Boolean(returnDate));
    this.refs.returnSummary.hidden = !isRoundTrip;
    this.refs.confirm.disabled = !departureDate || (isRoundTrip && !returnDate);
    this.refs.roundtrip.checked = isRoundTrip;
  }

  buildRedirectUrl() {
    const { config, departureDate, returnDate, isRoundTrip } = this.state;
    const url = safeURL(config.url);
    if (!url || !departureDate) return '';

    url.searchParams.set(config.dateParam || 'date', departureDate);
    url.searchParams.set('service', config.service || 'bus');

    if (config.fromName) {
      url.searchParams.set('nameFrom', config.fromName);
    }

    if (config.toName) {
      url.searchParams.set('nameTo', config.toName);
    }

    if (isRoundTrip && returnDate) {
      url.searchParams.set(config.returnDateParam || 'returnDate', returnDate);
    } else {
      url.searchParams.delete(config.returnDateParam || 'returnDate');
    }

    return url.toString();
  }

  confirm() {
    const redirectUrl = this.buildRedirectUrl();
    const detail = {
      departureDate: this.state.departureDate,
      returnDate: this.state.isRoundTrip ? this.state.returnDate : '',
      isRoundTrip: this.state.isRoundTrip,
      url: redirectUrl,
      trigger: this.state.trigger,
      config: this.state.config,
    };
    const event = new CustomEvent(DATE_RANGE_SELECTED_EVENT, {
      bubbles: true,
      cancelable: true,
      detail,
    });

    const eventTarget = this.state.trigger || this.root;
    const shouldContinue = eventTarget.dispatchEvent(event);
    if (!shouldContinue || this.state.config.mode === 'event' || !redirectUrl) {
      this.close();
      return;
    }

    if (this.state.config.target === '_blank') {
      window.open(redirectUrl, '_blank', 'noopener');
      this.close();
      return;
    }

    window.location.href = redirectUrl;
  }
}

export const getDailyveDateRangePicker = () => {
  if (!pickerInstance) {
    pickerInstance = new DailyveDateRangePicker();
  }

  return pickerInstance;
};

export const initDailyveDateRangePicker = () => {
  if (!isDelegationReady) {
    document.addEventListener('click', (event) => {
      const trigger = event.target.closest(TRIGGER_SELECTOR);
      if (!trigger) return;

      event.preventDefault();
      getDailyveDateRangePicker().open(trigger);
    });
    isDelegationReady = true;
  }

  window.DailyveDateRangePicker = {
    eventName: DATE_RANGE_SELECTED_EVENT,
    init: initDailyveDateRangePicker,
    open: (trigger, options = {}) => {
      const element = typeof trigger === 'string' ? document.querySelector(trigger) : trigger;
      if (!element) return null;
      return getDailyveDateRangePicker().open(element, options);
    },
    instance: getDailyveDateRangePicker,
  };
};
