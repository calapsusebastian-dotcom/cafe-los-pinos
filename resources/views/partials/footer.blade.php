  <footer class="footer" id="contacto">
    <a href="{{ route('home') }}" class="footer__logo" aria-label="Café Los Pinos - Inicio">
      <img src="{{ asset('img/logo-header.png') }}" alt="Café Los Pinos">
    </a>

    <form class="newsletter" action="{{ route('subscribe') }}" method="POST">
      @csrf
      <label for="email" class="newsletter__label">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
        {{ $content['newsletter_label'] }}
      </label>
      <div class="newsletter__row">
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Tu correo electrónico" required maxlength="190" autocomplete="email">
        <button type="submit">Suscribirse</button>
      </div>
      @if (session('newsletter_status'))
        <p class="newsletter__msg" role="status">{{ session('newsletter_status') }}</p>
      @endif
      @error('email')
        <p class="newsletter__msg newsletter__msg--error" role="alert">{{ $message }}</p>
      @enderror
    </form>

    <div class="footer__right">
      <div class="social">
        <a href="{{ $content['instagram_url'] }}" aria-label="Instagram"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg></a>
        <a href="{{ $content['facebook_url'] }}" aria-label="Facebook"><svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M14 8h3V4h-3a4 4 0 00-4 4v2H7v4h3v6h4v-6h3l1-4h-4V8z"/></svg></a>
        <a href="{{ App\Support\SiteContent::whatsappLink($content) }}" target="_blank" rel="noopener" aria-label="WhatsApp"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20l1.3-4.2A8 8 0 1112 20a8 8 0 01-3.9-1z"/><path d="M9 9c0 3 3 6 6 6l1-1.5-2-1-1 .8c-1-.5-2-1.500-2.500-2.500l.8-1-1-2z"/></svg></a>
      </div>
      <p class="footer__copy">{{ $content['footer_copy'] }}</p>
    </div>
  </footer>
