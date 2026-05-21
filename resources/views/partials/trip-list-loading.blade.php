@php
  $fallbackFrom = isset($_GET['nameFrom']) && !is_array($_GET['nameFrom'])
    ? sanitize_text_field(wp_unslash($_GET['nameFrom']))
    : '';
  $fallbackTo = isset($_GET['nameTo']) && !is_array($_GET['nameTo'])
    ? sanitize_text_field(wp_unslash($_GET['nameTo']))
    : '';
  $fallbackDate = isset($_GET['date']) && !is_array($_GET['date'])
    ? sanitize_text_field(wp_unslash($_GET['date']))
    : '';
  $fallbackRoute = ($fallbackFrom && $fallbackTo)
    ? $fallbackFrom . ' -> ' . $fallbackTo
    : 'Danh sách chuyến xe';
@endphp

<style>
  .dailyve-trip-boot,
  .dailyve-trip-boot * {
    box-sizing: border-box;
  }

  .dailyve-trip-boot {
    min-height: 100vh;
    overflow-x: hidden;
    background: #f8fafc;
    color: #0f172a;
    font-family: var(--font-sans, "Be Vietnam Pro", sans-serif);
  }

  .dailyve-trip-boot__hero {
    background: #ffffff;
    padding: 28px 0 36px;
  }

  .dailyve-trip-boot__inner,
  .dailyve-trip-boot__body {
    width: min(100% - 32px, 1280px);
    margin: 0 auto;
  }

  .dailyve-trip-boot__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border-radius: 999px;
    background: #eff6ff;
    padding: 8px 14px;
    color: #2563eb;
    font-size: 12px;
    font-weight: 800;
    line-height: 1.2;
    text-transform: uppercase;
  }

  .dailyve-trip-boot__eyebrow span {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #2563eb;
    box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.12);
  }

  .dailyve-trip-boot__headline {
    margin-top: 18px;
  }

  .dailyve-trip-boot__headline h1 {
    margin: 0;
    color: #0f172a;
    font-size: clamp(26px, 4vw, 46px);
    font-weight: 900;
    letter-spacing: 0;
    line-height: 1.12;
  }

  .dailyve-trip-boot__headline p {
    margin: 8px 0 0;
    color: #64748b;
    font-size: 15px;
    font-weight: 700;
  }

  .dailyve-trip-boot__search {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1.2fr) minmax(160px, 0.8fr) 160px;
    gap: 12px;
    margin-top: 26px;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    background: #ffffff;
    padding: 14px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
  }

  .dailyve-trip-boot__field,
  .dailyve-trip-boot__button,
  .dailyve-trip-boot__line,
  .dailyve-trip-boot__filter,
  .dailyve-trip-boot__badge,
  .dailyve-trip-boot__thumb,
  .dailyve-trip-boot__chips span,
  .dailyve-trip-boot__route,
  .dailyve-trip-boot__price,
  .dailyve-trip-boot__cta {
    background: linear-gradient(90deg, #eef2f7 0%, #f8fafc 42%, #e2e8f0 78%);
    background-size: 220% 100%;
    animation: dailyve-trip-boot-shimmer 1.35s ease-in-out infinite;
  }

  .dailyve-trip-boot__field,
  .dailyve-trip-boot__button {
    min-height: 58px;
    border-radius: 14px;
  }

  .dailyve-trip-boot__body {
    display: grid;
    grid-template-columns: 280px minmax(0, 1fr);
    gap: 20px;
    padding: 22px 0 96px;
  }

  .dailyve-trip-boot__filters,
  .dailyve-trip-boot__summary,
  .dailyve-trip-boot__card {
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
  }

  .dailyve-trip-boot__filters {
    align-self: start;
    min-height: 420px;
    padding: 18px;
  }

  .dailyve-trip-boot__line {
    height: 14px;
    border-radius: 999px;
  }

  .dailyve-trip-boot__line--title {
    width: 54%;
    height: 20px;
    margin-bottom: 22px;
  }

  .dailyve-trip-boot__line--heading {
    width: min(320px, 68vw);
    height: 22px;
  }

  .dailyve-trip-boot__line--meta {
    width: min(220px, 52vw);
    margin-top: 12px;
  }

  .dailyve-trip-boot__line--name {
    width: min(280px, 70%);
    height: 24px;
  }

  .dailyve-trip-boot__filter {
    height: 48px;
    margin-top: 14px;
    border-radius: 12px;
  }

  .dailyve-trip-boot__filter--short {
    width: 76%;
  }

  .dailyve-trip-boot__summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    min-height: 92px;
    padding: 18px;
  }

  .dailyve-trip-boot__badge {
    width: 190px;
    height: 36px;
    flex: 0 0 auto;
    border-radius: 999px;
  }

  .dailyve-trip-boot__cards {
    display: grid;
    gap: 18px;
    margin-top: 18px;
  }

  .dailyve-trip-boot__card {
    display: grid;
    grid-template-columns: 140px minmax(0, 1fr) 200px;
    gap: 20px;
    align-items: center;
    min-height: 164px;
    padding: 18px;
  }

  .dailyve-trip-boot__thumb {
    aspect-ratio: 4 / 3;
    border-radius: 14px;
  }

  .dailyve-trip-boot__card-main {
    min-width: 0;
  }

  .dailyve-trip-boot__chips {
    display: flex;
    gap: 10px;
    margin-top: 14px;
  }

  .dailyve-trip-boot__chips span {
    width: 92px;
    height: 16px;
    border-radius: 999px;
  }

  .dailyve-trip-boot__route {
    height: 46px;
    margin-top: 18px;
    border-radius: 12px;
  }

  .dailyve-trip-boot__fare {
    display: flex;
    min-height: 112px;
    flex-direction: column;
    justify-content: space-between;
    border-left: 1px solid #e2e8f0;
    padding-left: 20px;
  }

  .dailyve-trip-boot__price {
    align-self: flex-end;
    width: 112px;
    height: 30px;
    border-radius: 10px;
  }

  .dailyve-trip-boot__cta {
    width: 100%;
    height: 48px;
    border-radius: 14px;
  }

  @keyframes dailyve-trip-boot-shimmer {
    0% {
      background-position: 110% 0;
    }

    100% {
      background-position: -110% 0;
    }
  }

  @media (max-width: 1023px) {
    .dailyve-trip-boot__search {
      grid-template-columns: 1fr 1fr;
    }

    .dailyve-trip-boot__body {
      grid-template-columns: 1fr;
    }

    .dailyve-trip-boot__filters {
      display: none;
    }
  }

  @media (max-width: 767px) {
    .dailyve-trip-boot__hero {
      padding: 22px 0 28px;
    }

    .dailyve-trip-boot__inner,
    .dailyve-trip-boot__body {
      width: min(100% - 24px, 1280px);
    }

    .dailyve-trip-boot__headline h1 {
      font-size: 28px;
    }

    .dailyve-trip-boot__search,
    .dailyve-trip-boot__card,
    .dailyve-trip-boot__summary {
      border-radius: 14px;
    }

    .dailyve-trip-boot__search {
      grid-template-columns: 1fr;
      padding: 12px;
    }

    .dailyve-trip-boot__summary {
      align-items: flex-start;
      flex-direction: column;
    }

    .dailyve-trip-boot__badge {
      width: 160px;
    }

    .dailyve-trip-boot__card {
      grid-template-columns: 96px minmax(0, 1fr);
      gap: 14px;
      min-height: 210px;
      padding: 14px;
    }

    .dailyve-trip-boot__fare {
      grid-column: 1 / -1;
      min-height: 0;
      border-left: 0;
      border-top: 1px solid #e2e8f0;
      padding: 14px 0 0;
    }

    .dailyve-trip-boot__price {
      align-self: flex-start;
    }
  }

  @media (prefers-reduced-motion: reduce) {
    .dailyve-trip-boot__field,
    .dailyve-trip-boot__button,
    .dailyve-trip-boot__line,
    .dailyve-trip-boot__filter,
    .dailyve-trip-boot__badge,
    .dailyve-trip-boot__thumb,
    .dailyve-trip-boot__chips span,
    .dailyve-trip-boot__route,
    .dailyve-trip-boot__price,
    .dailyve-trip-boot__cta {
      animation: none;
    }
  }
</style>

<div class="dailyve-trip-boot" aria-live="polite" aria-busy="true">
  <section class="dailyve-trip-boot__hero">
    <div class="dailyve-trip-boot__inner">
      <div class="dailyve-trip-boot__eyebrow">
        <span></span>
        Hệ thống đặt vé Dailyve
      </div>

      <div class="dailyve-trip-boot__headline">
        <h1>Đang tải kết quả chuyến xe</h1>
        <p>
          {{ $fallbackRoute }}@if ($fallbackDate) · Ngày {{ $fallbackDate }}@endif
        </p>
      </div>

      <div class="dailyve-trip-boot__search" aria-hidden="true">
        <div class="dailyve-trip-boot__field dailyve-trip-boot__field--wide"></div>
        <div class="dailyve-trip-boot__field dailyve-trip-boot__field--wide"></div>
        <div class="dailyve-trip-boot__field"></div>
        <div class="dailyve-trip-boot__button"></div>
      </div>
    </div>
  </section>

  <section class="dailyve-trip-boot__body">
    <aside class="dailyve-trip-boot__filters" aria-hidden="true">
      <div class="dailyve-trip-boot__line dailyve-trip-boot__line--title"></div>
      <div class="dailyve-trip-boot__filter"></div>
      <div class="dailyve-trip-boot__filter"></div>
      <div class="dailyve-trip-boot__filter dailyve-trip-boot__filter--short"></div>
      <div class="dailyve-trip-boot__filter"></div>
    </aside>

    <main class="dailyve-trip-boot__results">
      <div class="dailyve-trip-boot__summary" aria-hidden="true">
        <div>
          <div class="dailyve-trip-boot__line dailyve-trip-boot__line--heading"></div>
          <div class="dailyve-trip-boot__line dailyve-trip-boot__line--meta"></div>
        </div>
        <div class="dailyve-trip-boot__badge"></div>
      </div>

      <div class="dailyve-trip-boot__cards" aria-hidden="true">
        @for ($index = 0; $index < 3; $index++)
          <div class="dailyve-trip-boot__card">
            <div class="dailyve-trip-boot__thumb"></div>
            <div class="dailyve-trip-boot__card-main">
              <div class="dailyve-trip-boot__line dailyve-trip-boot__line--name"></div>
              <div class="dailyve-trip-boot__chips">
                <span></span>
                <span></span>
              </div>
              <div class="dailyve-trip-boot__route"></div>
            </div>
            <div class="dailyve-trip-boot__fare">
              <div class="dailyve-trip-boot__price"></div>
              <div class="dailyve-trip-boot__cta"></div>
            </div>
          </div>
        @endfor
      </div>
    </main>
  </section>
</div>
