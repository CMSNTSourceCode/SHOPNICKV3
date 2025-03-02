<!-- BEGIN: Social Log in Area -->
<ul class="flex justify-center space-x-3">
  {{-- Facebook --}}
  @if (env('FACEBOOK_ACTIVE', false))
    <li>
      <a href="{{ route('auth.social', ['provider' => 'facebook']) }}" class="inline-flex h-10 w-10 flex-col items-center justify-center rounded-full bg-[#395599] text-2xl text-white">
        <img src="images/icon/fb.svg" alt="Facebook">
      </a>
    </li>
  @endif
  {{-- Google --}}
  @if (env('GOOGLE_ACTIVE', false))
    <li>
      <a href="{{ route('auth.social', ['provider' => 'google']) }}" class="inline-flex h-10 w-10 flex-col items-center justify-center rounded-full bg-[#EA4335] text-2xl text-white">
        <img src="images/icon/gp.svg" alt="Google">
      </a>
    </li>
  @endif
  {{-- Linkedin --}}
  @if (env('LINKEDIN_ACTIVE', false))
    <li>
      <a href="#!" class="inline-flex h-10 w-10 flex-col items-center justify-center rounded-full bg-[#0A63BC] text-2xl text-white">
        <img src="images/icon/in.svg" alt="Linkedin">
      </a>
    </li>
  @endif
  {{-- Github --}}
  @if (env('GITHUB_ACTIVE', false))
    <li>
      <a href="#!" class="inline-flex h-10 w-10 flex-col items-center justify-center rounded-full bg-gray-500 text-2xl text-white">
        <img src="images/icon/github.svg" alt="github">
      </a>
    </li>
  @endif
</ul>
<!-- END: Social Log In Area -->

{{-- @if (Route::is('login'))
    <p class="text-center font-light text-base text-textColor mt-8">
        {{ __('Don\'t have an account?') }}
        <a href="{{ route('register') }}" class="text-black font-bold">
            {{ __('Sign Up') }}
        </a>
    </p>
@elseif(Route::is('register'))
    <p class="text-center font-light text-base text-textColor mt-8">
        {{ __('Already registered?') }}
        <a href="{{ route('login') }}" class="text-black font-bold">
            {{ __('Sign in') }}
        </a>
    </p>
@endif --}}
