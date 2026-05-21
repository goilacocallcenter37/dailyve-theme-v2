const copyText = async (text) => {
  if (navigator.clipboard?.writeText) {
    await navigator.clipboard.writeText(text);
    return;
  }

  const input = document.createElement('textarea');
  input.value = text;
  input.setAttribute('readonly', '');
  input.style.position = 'absolute';
  input.style.left = '-9999px';
  document.body.appendChild(input);
  input.select();
  document.execCommand('copy');
  document.body.removeChild(input);
};

const initOfferCodes = () => {
  document.querySelectorAll('.dailyve-offer-card__save').forEach((button) => {
    if (button.dataset.offerCodeReady === 'true') {
      return;
    }

    button.dataset.offerCodeReady = 'true';

    button.addEventListener('click', async () => {
      const code = button.dataset.code;
      if (!code) {
        return;
      }

      try {
        await copyText(code);
        const original = button.textContent;
        button.textContent = 'Đã lưu!';
        button.classList.add('is-copied');
        window.setTimeout(() => {
          button.textContent = original;
          button.classList.remove('is-copied');
        }, 1800);
      } catch {
        button.textContent = code;
      }
    });
  });
};

const initServiceTabs = () => {
  const root = document.querySelector('[data-home-services]');
  if (!root) {
    return;
  }

  const panels = [...root.querySelectorAll('[data-service-panel]')];
  const typeButtons = [...root.querySelectorAll('[data-service-type]')];

  const showPanel = (panelId) => {
    panels.forEach((panel) => {
      const active = panel.dataset.servicePanel === panelId;
      panel.hidden = !active;
      panel.classList.toggle('is-active', active);
    });

    typeButtons.forEach((button) => {
      const active = button.dataset.serviceType === panelId;
      button.classList.toggle('is-active', active);
      button.setAttribute('aria-selected', active ? 'true' : 'false');
    });
  };

  typeButtons.forEach((button) => {
    button.addEventListener('click', () => {
      showPanel(button.dataset.serviceType);
    });
  });

  panels.forEach((panel) => {
    const tabButtons = [...panel.querySelectorAll('[data-service-tab]')];
    const contents = [...panel.querySelectorAll('[data-service-content]')];

    tabButtons.forEach((button) => {
      button.addEventListener('click', () => {
        const tabId = button.dataset.serviceTab;

        tabButtons.forEach((item) => {
          const active = item === button;
          item.classList.toggle('is-active', active);
          item.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        contents.forEach((content) => {
          const active = content.dataset.serviceContent === tabId;
          content.hidden = !active;
          content.classList.toggle('is-active', active);
        });
      });
    });
  });
};

const initTestimonialsSlider = () => {
  const root = document.querySelector('[data-home-testimonials]');
  if (!root) {
    return;
  }

  const track = root.querySelector('.dailyve-testimonials__track');
  const prev = root.querySelector('.dailyve-testimonials__nav--prev');
  const next = root.querySelector('.dailyve-testimonials__nav--next');

  if (!track) {
    return;
  }

  const scrollByCard = (direction) => {
    const card = track.querySelector('.dailyve-testimonial-card');
    const gap = 20;
    const amount = (card?.offsetWidth || 320) + gap;
    track.scrollBy({ left: direction * amount, behavior: 'smooth' });
  };

  prev?.addEventListener('click', () => scrollByCard(-1));
  next?.addEventListener('click', () => scrollByCard(1));
};

const initPressSlider = () => {
  const root = document.querySelector('.dailyve-press');
  if (!root) {
    return;
  }

  const track = root.querySelector('[data-press-track]');
  const prev = root.querySelector('[data-press-nav-prev]');
  const next = root.querySelector('[data-press-nav-next]');

  if (!track) {
    return;
  }

  const scrollByCard = (direction) => {
    const card = track.querySelector('.dailyve-press-card');
    const gap = 24;
    const amount = (card?.offsetWidth || 340) + gap;
    track.scrollBy({ left: direction * amount, behavior: 'smooth' });
  };

  prev?.addEventListener('click', () => scrollByCard(-1));
  next?.addEventListener('click', () => scrollByCard(1));
};

export const initHomePage = () => {
  initOfferCodes();

  if (!document.querySelector('.dailyve-home')) {
    return;
  }

  initServiceTabs();
  initTestimonialsSlider();
  initPressSlider();
};
