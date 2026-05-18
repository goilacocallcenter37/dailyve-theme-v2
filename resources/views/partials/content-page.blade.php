@php
  the_content();
@endphp

@if ($pagination())
  <nav class="page-nav" aria-label="Page">
    {!! $pagination !!}
  </nav>
@endif
