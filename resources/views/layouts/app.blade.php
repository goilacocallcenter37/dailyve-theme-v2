<!doctype html>
<html {!! language_attributes() !!}>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
      do_action('get_header');
      wp_head();
    @endphp

    <script>
      window.generic_data = {
        ajax_url: '{{ admin_url('admin-ajax.php') }}',
        nonce: '{{ wp_create_nonce('ams_vexe') }}'
      };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
  </head>

  <body class="{{ implode(' ', get_body_class()) }}">
    @php
      wp_body_open();
    @endphp

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      @include('sections.header')

      <main id="main" class="main">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar">
          @yield('sidebar')
        </aside>
      @endif

      @include('sections.footer')
    </div>

    @php
      do_action('get_footer');
      wp_footer();
    @endphp
  </body>
</html>
