<script setup>
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
  groupId: {
    type: String,
    default: 0,
  },
})

const loading = ref(false)
const products = ref([])

const getProducts = async () => {
  loading.value = true
  try {
    const { data: result } = await axios.get('/api/stores/boosting-game', {
      params: {
        limit: 100,
        group_id: props.groupId
      },
    })

    products.value = result.data?.data || []
  } catch (error) {
    Swal.fire('Oops...', $catchMessage(error), 'error')
  } finally {
    loading.value = false
  }
}

const formatCurrency = (number, currency = 'VND', maxinum = 2) => {
  return $formatCurrency(number, currency, maxinum)
}

const options = computed(() => {
  return products.value.map((item) => {
    return {
      label: item.name + ' - ' + formatCurrency(item.price),
      value: item.id,
    }
  }) || []
})

const product = computed(() => {
  return products.value.find((item) => item.id === formBuy.value.package)
})

const handleChange = (value) => {
  console.log(`selected ${value}`);
};

const filterOption = (input, option) => {
  return option.value.toLowerCase().indexOf(input.toLowerCase()) >= 0;
};

const totalPrice = computed(() => {
  return product.value?.price || 0
})

const onSubmit = async (value) => {

  if (product.value?.id === undefined) {
    return Swal.fire('Oops...', $__t('Vui lòng chọn gói cần thuê'), 'error')
  }

  if (value.input_user === '' && value.input_pass === '' && value.input_extra === '') {
    return Swal.fire('Oops...', $__t('Vui lòng nhập tài khoản cần cày'), 'error')
  }

  const confirm = await Swal.fire({
    icon: 'question',
    title: $__t('Bạn chắc chứ?'),
    text: `${$__t('Bạn sẽ mua gói')} ${product.value?.name} ${$__t('với giá')} ${formatCurrency(totalPrice.value)}?`,
    showCancelButton: true,
    confirmButtonText: $__t('Đồng ý'),
    cancelButtonText: $__t('Hủy'),
  })

  if (confirm.isConfirmed !== true) return

  Swal.fire({
    icon: 'info',
    title: $__t('Đang xử lý!'),
    html: $__t('Không được tắt trang này, vui lòng đợi trong giây lát!'),
    timerProgressBar: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowEnterKey: false,
    didOpen: () => {
      Swal.showLoading()
    },
    willClose: () => { },
  })

  try {
    const { data: result } = await axios.post('/api/stores/boosting-game/' + product.value.code + '/buy', value)

    Swal.fire('Great !', result.message, 'success').then(() => {
      window.open('/account/orders/boosting-game/' + result.data.code, '_self')
    })
  } catch (error) {
    Swal.fire('Oops...', $catchMessage(error), 'error')
  }
}

const formBuy = ref({
  package: null,
  order_note: '',
  input_user: '',
  input_pass: '',
  input_extra: '',
})

const __t = (key) => {
  return $__t(key)
}

onMounted(() => {
  getProducts()
})
</script>
<template>
  <section>
    <a-card>
      <a-spin :spinning="loading">
        <a-form layout="vertical" :model="formBuy" @finish="onSubmit">
          <a-form-item :label="__t('Chọn Gói Cần Thuê')" name="package" :rules="[{
            required: true,
            message: __t('Vui lòng chọn gói cần thuê')
          }]">
            <a-select v-model:value="formBuy.package" show-search :placeholder="__t('Chọn gói dịch vụ cần thuê')" :options="options" :filter-option="filterOption"></a-select>
            <!-- name of package -->
            <a-alert v-if="product?.name" type="info" class="mt-2">
              <template #message>
                {{ product.name }} - {{ formatCurrency(product.price) }}
              </template>
            </a-alert>
            <a-alert v-if="product?.descr" type="error" class="mt-2">
              <template #message>
                {{ product.descr }}
              </template>
            </a-alert>
          </a-form-item>
          <a-row :gutter="16">
            <a-col :xs="24" :md="8">
              <a-form-item :label="__t('Tài Khoản')" name="input_user">
                <a-input v-model:value="formBuy.input_user" :placeholder="__t('Nhập tài khoản cần cày')"></a-input>
              </a-form-item>
            </a-col>
            <a-col :xs="24" :md="8">
              <a-form-item :label="__t('Mật Khẩu')" name="input_pass">
                <a-input v-model:value="formBuy.input_pass" :placeholder="__t('Nhập mật khẩu của tài khoản đó')"></a-input>
              </a-form-item>
            </a-col>
            <a-col :xs="24" :md="8">
              <a-form-item :label="__t('Cookie / 2FA')" name="input_extra">
                <a-input v-model:value="formBuy.input_extra" :placeholder="__t('Có thể nhập chuỗi 2FA, link game pass hoặc cookie liên quan')"></a-input>
                <div class="text-danger-600 font-bold">{{ __t('Dữ liệu này không bắt buộc, có thể bỏ trống') }}</div>
              </a-form-item>
            </a-col>
          </a-row>
          <a-form-item :label="__t('Ghi Chú')" name="order_note">
            <a-textarea v-model:value="formBuy.order_note" :placeholder="__t('Nhập ghi chú cho admin nếu có...')" :rows="3"></a-textarea>
          </a-form-item>
          <a-form-item>
            <div class="text-center space-y-3">
              <div>
                <h4><span class="text-danger-600">{{ formatCurrency(totalPrice) }}</span></h4>
                <h6 class="text-sm">{{ __t('Tổng Thanh Toán') }}</h6>
              </div>
              <a-button type="primary" htmlType="submit">{{ __t('Tạo Đơn Hàng') }}</a-button>
            </div>
          </a-form-item>
        </a-form>
      </a-spin>
    </a-card>
  </section>
</template>