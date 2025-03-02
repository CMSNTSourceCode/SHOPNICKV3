<script setup>
import { computed, onMounted, ref } from 'vue'
// iconify-icon

const props = defineProps({
  groupId: {
    type: String,
    default: 0,
  },
})

const form = ref({
  price: '',
  search: '',
  display_by: '',
})

const pagination = ref({
  page: 1,
  limit: 24,
  total_rows: 0,
  total_page: 0,
  visible_pages: [],
})

const visiblePages = computed(() => {
  const { page, total_page, visible_pages } = pagination.value
  const visibleRange = 5

  let startPage = Math.max(page - Math.floor(visibleRange / 2), 1)
  let endPage = Math.min(startPage + visibleRange - 1, total_page)

  if (endPage - startPage + 1 < visibleRange) {
    startPage = Math.max(endPage - visibleRange + 1, 1)
  }

  return Array.from(
    { length: endPage - startPage + 1 },
    (_, i) => startPage + i
  )
})

const goToPage = (page) => {
  if (pagination.value.page === page) {
    return
  }

  pagination.value.page = page

  getProducts()
}

const onPrevPage = () => {
  if (pagination.value.page <= 1) return

  pagination.value.page--

  getProducts()
}

const onNextPage = () => {
  if (pagination.value.page >= pagination.value.total_page) return

  pagination.value.page++

  getProducts()
}

const orderBy = computed(() => {
  if (__defaultLang && __defaultLang === 'vn') {
    return [
      {
        label: 'Mặc định',
        value: '',
      },
      {
        label: 'Mới nhất',
        value: 'created_at_desc',
      },
      {
        label: 'Cũ nhất',
        value: 'created_at_asc',
      },
      {
        label: 'Giá thấp đến cao',
        value: 'price_asc',
      },
      {
        label: 'Giá cao đến thấp',
        value: 'price_desc',
      },
    ]
  } else {
    return [
      {
        label: 'Default',
        value: '',
      },
      {
        label: 'Newest',
        value: 'created_at_desc',
      },
      {
        label: 'Oldest',
        value: 'created_at_asc',
      },
      {
        label: 'Price low to high',
        value: 'price_asc',
      },
      {
        label: 'Price high to low',
        value: 'price_desc',
      },
    ]
  }
})

const priceRange = computed(() => {
  if (__defaultLang && __defaultLang === 'vn') {
    return [
      {
        label: 'Tất cả',
        value: '',
      },
      {
        label: 'Dưới 100.000đ',
        value: '0-100000',
      },
      {
        label: '100.000đ - 200.000đ',
        value: '100000-200000',
      },
      {
        label: '200.000đ - 500.000đ',
        value: '200000-500000',
      },
      {
        label: '500.000đ - 1.000.000đ',
        value: '500000-1000000',
      },
      {
        label: 'Trên 1.000.000đ',
        value: '1000000-0',
      },
    ]
  } else {
    return [
      {
        label: 'All',
        value: '',
      },
      {
        label: 'Under $5',
        value: '0-5',
      },
      {
        label: '$5 - $10',
        value: '5-10',
      },
      {
        label: '$10 - $20',
        value: '10-20',
      },
      {
        label: '$20 - $50',
        value: '20-50',
      },
      {
        label: 'Over $50',
        value: '50-0',
      },
    ]
  }
})

const loading = ref(false)
const products = ref([])
const listIGame = ref([])

const getIGame = async () => {
  try {
    const { data: result } = await axios.get('/api/stores/list-ingame')

    listIGame.value = result.data || []
  } catch (error) {
    Swal.fire('Oops...', $catchMessage(error), 'error')
  }
}

const getProducts = async () => {
  loading.value = true
  try {
    const { data: result } = await axios.get('/api/stores/items', {
      params: {
        ...form.value,
        group_id: props.groupId,
        ...pagination.value,
      },
    })

    products.value = result.data?.data || []
    pagination.value = result.data?.meta || []
  } catch (error) {
    Swal.fire('Oops...', $catchMessage(error), 'error')
  } finally {
    loading.value = false

    scollUp()
  }
}

const onFilter = () => {
  pagination.value.page = 1

  getProducts()
}

const resetFilter = () => {
  form.value = {
    price: '',
    search: '',
  }

  pagination.value.page = 1

  getProducts()
}

const formatCurrency = (number, currency = 'VND', maxinum = 2) => {
  return $formatCurrency(number, currency, maxinum)
}

const open = ref(false)
const product = ref(null)
const formBuy = ref({
  Lien_He: '',
  Mat_Khau: '',
  Ten_Game: '',
  Tai_Khoan: '',
  user_note: '',
  isConfirm: false,
  Dang_Nhap_Bang: null,
})
const showModal = async (item) => {
  if ((product.value = item)) {
    open.value = true
  }
}
const handleCancel = () => {
  open.value = false
}
const handleOk = () => {
  open.value = false
}

const buyItem = async (product, form) => {
  if (product?.code === undefined) {
    return
  }

  if (product.type === 'addfriend' && form.Ten_In_Game === '') {
    return Swal.fire(
      'Oops...',
      $__t('Vui lòng nhập tên tài khoản nhận'),
      'error'
    )
  } else if (product.type === 'user_pass' && form.Tai_Khoan === '') {
    return Swal.fire('Oops...', $__t('Vui lòng nhập tài khoản'), 'error')
  } else if (product.type === 'user_pass' && form.Mat_Khau === '') {
    return Swal.fire('Oops...', $__t('Vui lòng nhập mật khẩu'), 'error')
  }

  const confirm = await Swal.fire({
    icon: 'question',
    title: $__t('Bạn chắc chứ?'),
    text: `${$__t('Bạn sẽ mua vật phẩm')} #${product.code} ${$__t('với giá')} ${product.payment_str
      }?`,
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
    const { data: result } = await axios.post(
      '/api/stores/items/' + product.code + '/buy',
      form
    )

    Swal.fire('Great!', result.message, 'success').then(() => {
      window.open('/account/orders/items/' + result.data.code, '_self')
    })
  } catch (error) {
    Swal.fire('Oops...', $catchMessage(error), 'error')
  }
}

const scollUp = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  })
}

const __t = (key) => {
  return $__t(key)
}

const loginWithOptions = computed(() => window.loginWith)

onMounted(() => {
  // getIGame()
  getProducts()
})
</script>
<template>
  <section>
    <a-spin :spinning="loading">
      <form class="mb-5 mt-5" @submit.prevent="onFilter">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div>
            <label class="form-label">{{ __t('Chọn mức giá') }}: </label>
            <a-select v-model:value="form.price" :placeholder="__t('Chọn mức giá')" style="width: 100%">
              <a-select-option v-for="item in priceRange" :key="item.value" :value="item.value">{{ item.label }}</a-select-option>
            </a-select>
          </div>
          <div>
            <label class="form-label">{{ __t('Sắp Xếp Theo:') }}</label>
            <a-select v-model:value="form.display_by" style="width: 100%">
              <a-select-option v-for="item in orderBy" :key="item.value" :value="item.value">{{ item.label }}</a-select-option>
            </a-select>
          </div>
          <div>
            <label class="form-label">{{ __t('Tìm kiếm') }}: </label>
            <a-input v-model:value="form.search" :placeholder="__t('Tìm kiếm mã sản phẩm, trang phục,...')" style="width: 100%" />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <button class="btn btn-sm btn-primary w-full lg:mt-[32px]">
                <i class="fas fa-search"></i> {{ __t('Tìm kiếm') }}
              </button>
            </div>
            <div>
              <button class="btn btn-sm btn-danger w-full lg:mt-[32px]" type="button" @click="resetFilter()">
                <i class="fas fa-trash"></i> {{ __t('Đặt lại') }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </a-spin>

    <a-row :gutter="18" v-if="loading && products?.length === 0">
      <a-col :xs="12" :sm="8" :lg="6" v-for="item in 4" :key="item">
        <a-card class="mb-3">
          <a-skeleton active></a-skeleton>
        </a-card>
      </a-col>
    </a-row>
    <a-spin v-else :spinning="loading">
      <a-row :gutter="18">
        <a-col :xs="12" :md="6" :lg="4" v-for="item in products" :key="item.id" class="mb-2 mt-2 cursor-pointer">
          <a-badge-ribbon color="red" :text="`${item.discount}%`" :class="{ hidden: item.discount <= 0 }" placement="start">
            <a-badge-ribbon color="pink" :text="`MS: ${item.code}`" placement="end">
              <div style="background: transparent" class="border border-primary rounded-lg p-[1px]">
                <div class="text-center">
                  <a-image :src="item.image" alt="" style="max-width: 300px; max-height: 400px" class="rounded-t-lg" />
                  <a-tooltip :title="__t('Đã bán') + ' ' + item.sold_count" placement="bottom">
                    <h4 class="text-lg mt-[-6px] font-semibold bg-danger-500 text-white whitespace-normal">
                      {{ item.name }}
                    </h4>
                  </a-tooltip>
                </div>
                <div class="p-3">
                  <div class="text-center mb-[5px]">
                    <!-- <span class="font-semibold text-lg">{{ __t('Đã bán') }}: {{ item.sold_count }}</span> -->
                  </div>
                  <div class="text-center space-y-3 lg:flex lg:justify-between lg:gap-3 lg:space-y-0">
                    <!-- <a-tooltip
                      :title="
                        item.discount !== 0
                          ? `${__t('Giá gốc')} ${formatCurrency(item.payment)}`
                          : ''
                      "
                    >
                      <a-button type="dashed" block class="dark:text-white"
                        ><i class="fas fa-credit-card me-2"></i>
                        {{ item.payment_str }}</a-button
                      >
                    </a-tooltip> -->
                    <button class="btn btn-sm btn-primary w-full" @click="showModal(item)">
                      <i class="fas fa-shopping-cart me-2 hidden lg:inline-block"></i><span class="">{{ item.payment_str }}</span>
                    </button>
                  </div>
                </div>
              </div>
            </a-badge-ribbon>
          </a-badge-ribbon>
        </a-col>
      </a-row>
    </a-spin>
    <a-card v-if="products?.length === 0 && !loading">
      <a-empty :description="__t('Không tìm thấy vật phẩm nào trong nhóm này')"></a-empty>
    </a-card>
    <div class="text-center mt-3">
      <a-spin :spinning="loading">
        <ul class="list-none">
          <li class="inline-block">
            <a href="javascript:void(0)" @click="onPrevPage()"
              class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px] pl-2">
              <iconify-icon icon="material-symbols:arrow-back-ios-rounded"></iconify-icon>
            </a>
          </li>
          <li v-for="page in visiblePages" :key="page" class="inline-block">
            <a href="javascript:void(0)" @click="goToPage(page)"
              class="flex items-center justify-center w-6 h-6 bg-slate-100 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300"
              :class="{ 'p-active': pagination.page === page }">
              {{ page !== -1 ? page : '...' }}</a>
          </li>
          <li class="inline-block">
            <a href="javascript:void(0)" @click="onNextPage()"
              class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px]">
              <iconify-icon icon="material-symbols:arrow-forward-ios-rounded"></iconify-icon>
            </a>
          </li>
        </ul>
      </a-spin>
    </div>

    <a-modal v-model:open="open" :width="820" :title="`${__t('Mua Vật Phẩm')}: ${product?.name}`" @ok="handleOk">
      <template #footer>
        <a-button key="back" @click="handleCancel">{{ __t('Huỷ') }}</a-button>
        <a-button key="submit" type="primary" :loading="loading" :disabled="formBuy?.isConfirm !== true" @click="buyItem(product, formBuy)">{{ __t('Thanh Toán') }}</a-button>
      </template>
      <hr class="mb-3" />
      <div v-if="product?.type === 'addfriend'">
        <a-row :gutter="12" class="space-y-3">
          <a-col :xs="24" :md="12">
            <div class="mb-3" v-html="product?.description"></div>
            <div class="space-y-3">
              <a-tag v-for="(ig, idx) in product?.ingame_list" :key="idx" color="success" class="copy cursor-pointer" :data-clipboard-text="ig">{{ ig }}</a-tag>
            </div>
          </a-col>
          <a-col :xs="24" :md="12">
            <a-form :model="formBuy" class="space-y-3">
              <a-input :value="product?.name" :addon-after="__t('Sản phẩm')" disabled />
              <a-input :value="product?.payment_str" :addon-after="__t('Giá gốc')" disabled />
              <a-input :value="product?.discount" :addon-after="__t('Giảm giá')" addon-before="%" disabled />
              <div v-if="product?.type == 'addfriend'">
                <a-input v-model:value="formBuy.Tai_Khoan" placeholder="admin#1234" :addon-after="__t('Tài khoản nhận')" autocomplete="0" />
                <a-checkbox v-model:checked="formBuy.isConfirm" class="mt-2">
                  {{ __t('Tôi đã kết bạn') }}
                  <span class="text-danger-600">{{ __t('TẤT CẢ') }}</span>
                  ingame
                </a-checkbox>
              </div>
              <div v-else>
                <a-checkbox v-model:checked="formBuy.isConfirm">
                  {{ __t('Tôi cung cấp đúng tài khoản và mật khẩu') }}
                </a-checkbox>
              </div>
            </a-form>
          </a-col>
        </a-row>
      </div>
      <div v-else>
        <a-row :gutter="12">
          <a-col :xs="24" :md="12">
            <div class="space-y-3 mb-3">
              <a-input v-model:value="formBuy.Tai_Khoan" :placeholder="__t('Tài khoản')" :addon-after="__t('Tài khoản')" autocomplete="0" />
              <a-input v-model:value="formBuy.Mat_Khau" :placeholder="__t('Mật khẩu')" :addon-after="__t('Mật khẩu')" type="password" autocomplete="0" />
              <a-input v-model:value="formBuy.Ten_Game" :placeholder="__t('Tên Trong Game')" :addon-after="__t('Tên Trong Game')" autocomplete="0" />
              <a-input v-model:value="formBuy.Lien_He" :placeholder="__t('Liên hệ (Zalo, Facebook...)')" :addon-after="__t('Liên hệ (Zalo, Facebook...)')" type="text" autocomplete="0" />
              <!-- <a-input v-model:value="formBuy.user_note" :placeholder="__t('Ghi chú cho admin')" :addon-after="__t('Ghi chú cho admin')" /> -->
              <!-- <a-select v-model:value="formBuy.Dang_Nhap_Bang" placeholder="Chọn cách đăng nhập" style="width: 100%">
                <a-select-option value="Roblox">{{ __t('Đăng Nhập Bằng') }} Roblox</a-select-option>
                <a-select-option value="Riot">{{ __t('Đăng Nhập Bằng') }} Riot</a-select-option>
                <a-select-option value="Facebook">{{ __t('Đăng Nhập Bằng') }} Facebook</a-select-option>
                <a-select-option value="Google">{{ __t('Đăng Nhập Bằng') }} Google</a-select-option>
                <a-select-option value="Microsoft">{{ __t('Đăng Nhập Bằng') }} Microsoft</a-select-option>
                <a-select-option value="Other">{{
                  __t('Đăng Nhập ở đâu thì ghi chú')
                  }}</a-select-option>
              </a-select> -->
              <a-select v-model:value="formBuy.Dang_Nhap_Bang" placeholder="Chọn phương thức đăng nhập có sẵn" :options="loginWithOptions" style="width: 100%"></a-select>
            </div>
            <div>
              <a-badge v-for="(text, idx) in product?.highlights" :key="idx" color="red" :text="text"></a-badge>
            </div>
          </a-col>
          <a-col :xs="24" :md="12">
            <a-form class="space-y-3">
              <a-input :value="product?.name" :addon-after="__t('Sản phẩm')" disabled />
              <a-input :value="product?.payment_str" :addon-after="__t('Giá gốc')" disabled />
              <a-input :value="product?.discount" :addon-after="__t('Giảm giá')" addon-before="%" disabled />
              <a-input v-model:value="formBuy.user_note" :placeholder="__t('Ghi chú cho admin')" :addon-after="__t('Ghi chú cho admin')" />
              <div v-if="product?.type == 'addfriend'">
                <a-input v-model:value="formBuy.Ten_In_Game" placeholder="admin#1234" :addon-after="__t('Tài khoản nhận')" />
                <a-checkbox v-model:checked="formBuy.isConfirm" class="mt-2">
                  {{ __t('Tôi đã kết bạn') }}
                  <span class="text-danger-600">{{ __t('TẤT CẢ') }}</span>
                  ingame
                </a-checkbox>
              </div>
              <div v-else>
                <a-checkbox v-model:checked="formBuy.isConfirm">
                  {{ __t('Tôi cung cấp đúng tài khoản và mật khẩu') }}
                </a-checkbox>
              </div>
            </a-form>
          </a-col>
        </a-row>
      </div>
    </a-modal>
  </section>
</template>