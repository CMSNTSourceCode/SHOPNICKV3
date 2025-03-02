<div class="card">
  <div class="card-body flex flex-col p-6">
    <div class="card-text h-full space-y-4">
      <form action="/api/withdraws/store" method="POST" id="form-withdraw" class="space-y-3">
        <input type="hidden" name="id" value="{{ $inventory->id }}">
        <div class="input-area">
          <label for="balance_2" class="form-label">{{ __t('Trò Chơi') }} : {{ $inventory->inventory_var->name }}</label>
          <input id="balance_2" name="balance_2" type="text" class="form-control" value="{{ number_format($inventory->value) }} {{ $inventory->inventory_var->unit }}" disabled>
        </div>
        <div class="input-area">
          <label for="amount" class="form-label">{{ __t('Số Lượng Rút') }}</label>
          <select name="amount" id="amount" class="form-control">
            @foreach ($inventory->inventory_var->form_packages as $value => $name)
              <option value="{{ $value }}">{{ $name }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex flex-col justify-between md:flex-row md:gap-4">
          @foreach ($inventory->inventory_var->form_inputs as $index => $input)
            @if ($input['type'] === 'option')
              <div class="input-area">
                <label for="{{ $input['type'] }}" class="form-label">{{ $input['label'] }}</label>
                <select name="{{ $input['type'] }}" id="{{ $input['type'] }}" class="form-control">
                  @foreach ($input['options'] as $value => $name)
                    <option value="{{ $value }}">{{ $name }}</option>
                  @endforeach
                </select>
              </div>
            @else
              <div class="input-area basis-1/2">
                <label for="{{ $input['type'] }}" class="form-label">{{ $input['label'] }}</label>
                <input id="{{ $input['type'] }}" name="arr_inputs[{{ $index }}]" type="{{ $input['type'] }}" class="form-control " required>
              </div>
            @endif
          @endforeach
        </div>
        {{-- <div class="input-area">
          <label for="user_note" class="form-label">{{ __t('Ghi Chú Cho Admin') }}</label>
          <textarea id="user_note" name="user_note" class="form-control" rows="3" placeholder="{{ __t('Ghi chú cho yêu cầu rút thưởng') }}" required></textarea>
        </div> --}}
        <div class="input-area">
          <button class="btn btn-primary w-full" id="frm_submit" type="submit">{{ __t('Rút Thưởng Ngay') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#form-withdraw").submit(e => {
      e.preventDefault()

      let formData = new FormData(e.target),
        btnSubmit = e.target.querySelector("button[type=submit]")

      $setLoading(btnSubmit)

      axios.post("/api/withdraws/store", formData)
        .then(res => {
          Swal.fire("Thành công", res.data.message, "success").then(() => {
            $(".load_form").load('/account/withdraws-v2/forms?id={{ $inventory->id }}')
          })
        })
        .catch(err => {
          Swal.fire("Thất bại", $catchMessage(err), "error")
        })
    })
  })
</script>
