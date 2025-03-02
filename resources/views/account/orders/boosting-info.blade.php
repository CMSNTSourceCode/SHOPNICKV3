@section('title', __t($pageTitle))
<x-app-layout>
  <section class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div class="card">
        <header class=" card-header noborder">
          <h4 class="card-title">{{ __t('Thông Tin Giao Dịch') }} <span class="text-danger-500">{{ $item->code }}</span></h4>
        </header>
        <div class="card-body px-6 pb-6">
          <form class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Sản Phẩm') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ $item->name ?? '-' }}" disabled>
              </div>
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Thanh Toán') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ Helper::formatCurrency($item->payment) }}" disabled>
              </div>
            </div>
            <div class="input-area">
              <label for="group" class="form-label">{{ __t('Thuộc Nhóm') }}</label>
              <input type="text" class="form-control !pr-12" value="{{ $item->group->name }}" disabled>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Ngày Mua') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ $item->created_at }}" disabled>
              </div>
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Ngày Cập Nhật') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ $item->updated_at }}" disabled>
              </div>
            </div>
            <div class="text-center">
              Trạng thái: <span class="font-bold @if ($item->status === 'Completed') text-green-600 @else text-red-600 @endif">{{ Helper::formatStatus($item->status, 'text') }}</span>
            </div>
          </form>
        </div>
      </div>
      <div class="card">
        <header class=" card-header noborder">
          <h4 class="card-title">{{ __t('Thông Tin Đơn Hàng') }} <span class="text-green-500">{{ $item->code }}</span></h4>
        </header>
        <div class="card-body px-6 pb-6">
          <form class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Thông Tin') }}</label>
                <div class="relative">
                  <input type="text" class="form-control !pr-12" value="{{ $item->input_user }}" disabled>
                  <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center copy"
                    data-clipboard-text="{{ $item->input_user }}" type="button">
                    <iconify-icon icon="heroicons-solid:save"></iconify-icon>
                  </button>
                </div>
              </div>
              <div class="input-area">
                <label for="password" class="form-label">{{ __t('Mật Khẩu') }}</label>
                <div class="relative">
                  <input type="text" class="form-control !pr-12" value="{{ $item->input_pass ?? '- undefined -' }}" disabled>
                  <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center copy"
                    data-clipboard-text="{{ $item->input_pass ?? '' }}" type="button">
                    <iconify-icon icon="heroicons-solid:save"></iconify-icon>
                  </button>
                </div>
              </div>
            </div>
            <div class="input-area">
              <label for="order_note" class="form-label">{{ __t('Ghi Chú') }}</label>
              <textarea name="order_note" id="order_note" class="form-control !pr-12" disabled>{{ $item->order_note }}</textarea>
            </div>
            <div class="input-area">
              <label for="admin_note" class="form-label">{{ __t('Admin Cập Nhật') }}</label>
              <textarea name="admin_note" id="admin_note" class="form-control !pr-12" disabled>{{ $item->admin_note }}</textarea>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="text-center">
      <a href="{{ route('account.orders.boosting') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> {{ __t('Quay Lại') }}</a>
    </div>
  </section>
</x-app-layout>
