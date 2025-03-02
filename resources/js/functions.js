// Path: resources/js/app.js
'use strict'
// !!! Author : baodev@cmsnt.co
// !!! Date : 2022-11-20
// !!! Description : Utils for any project

console.log(`info: v1.0.1`)
console.log(`author: fb.com/baoint`)
console.log(`websie: cmsnt.co`)

// core functions & require jquery
window.$getResponseMessage = function (error) {
  if (error.response && error.response.data) {
    return error.response.data.message
  }
  return error.message || 'Unknown error'
}

window.$getRequestMessage = function (error) {
  return error.message || 'Error in request'
}

window.$getStatusMessage = function (error) {
  if (error.responseJSON) {
    return error.responseJSON.message
  }
  return error.statusText
}

window.$getErrorMessage = function (error) {
  return error.message || error.stack
}

window.$catchMessage = function (error) {
  let message = 'System error occurred'

  message = error.isAxiosError
    ? error.response
      ? $getResponseMessage(error)
      : error.request
      ? $getRequestMessage(error)
      : message
    : error.status
    ? $getStatusMessage(error)
    : $getErrorMessage(error)

  // console.log(error.response || error.request || error)

  return message
}

window.$parseError = function (error) {
  try {
    let message = 'System error occurred'

    message = error.isAxiosError
      ? error.response
        ? $getResponseMessage(error)
        : error.request
        ? $getRequestMessage(error)
        : message
      : error.status
      ? $getStatusMessage(error)
      : $getErrorMessage(error)

    console.log(error.response || error.request || error)

    return message
  } catch (e) {
    console.log(e)
    return 'Error occurred while handling error'
  }
}

window.$formatCurrency = function (number, currency = 'VND', maxinum = 2) {
  const formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
  })

  return formatter.format(number) + ' ₫'
}

window.$formatNumber = function (number) {
  return new Intl.NumberFormat('en-US').format(number)
}

window.$formatDateTime = function (date, format = 'YYYY-MM-DD HH:mm:ss') {
  return moment(date).format(format)
}

window.$formatStatus = function (status) {
  switch (status) {
    case 'Running':
      return `<span class="badge bg-primary rounded-lg" style="background-color: #0174BE">Đang chạy</span>`
    case 'Pending':
      return `<span class="badge bg-warning rounded-lg" style="background-color: #FFC436">Đang chờ</span>`
    case 'Preparing':
      return `<span class="badge bg-info rounded-lg" style="background-color: #B15EFF">Đang chuẩn bị</span>`
    case 'Canceled':
      return `<span class="badge bg-danger rounded-lg" style="background-color: #CE5A67">Đã hủy</span>`
    case 'Completed':
      return `<span class="badge bg-success rounded-lg text-white" style="background-color: #1A5D1A">Thành công</span>`
    case 'Refund':
      return `<span class="badge bg-danger rounded-lg text-white" style="background-color: #862B0D">Hoàn tiền</span>`
    case 'WaitingForRefund':
      return `<span class="badge bg-secondary rounded-lg text-white" style="background-color: #4E4FEB">Đang huỷ</span>`
    case 'Holding':
      return `<span class="badge bg-warning rounded-lg text-white" style="background-color: #3F2305">Đang giữ</span>`
    case 'Paused':
      return `<span class="badge bg-danger rounded-lg" style="background-color: #FF2171">Tạm dừng</span>`
    case 'Expired':
      return `<span class="badge bg-danger rounded-lg" style="background-color: #FF6666">Hết hạn</span>`
    case 'Active':
      return `<span class="badge bg-success rounded-lg" style="background-color: #4FC0D0">Hoạt động</span>`
    case 'Cancelled':
      return `<span class="badge bg-danger rounded-lg" style="background-color: #FF6666">Đã hủy</span>`
    case 'Rejected':
      return `<span class="badge bg-danger rounded-lg" style="background-color: #FF6666">Từ chối</span>`
    case 'Approved':
      return `<span class="badge bg-success rounded-lg" style="background-color: #4FC0D0">Đã duyệt</span>`
    default:
      return `<span class="badge bg-secondary rounded-lg text-white" style="background-color: #213363">${status}</span>`
  }

  return status
}

window.$setLoading = function (elm) {
  $(elm).attr('disabled', true).addClass('process')
}

window.$removeLoading = function (elm) {
  $(elm).attr('disabled', false).removeClass('process')
}

window.$formatDate = function (date, format = 'YYYY-MM-DD HH:mm:ss') {
  return moment(date).format(format)
}

window.$isURL = function (str) {
  let regex =
    /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/
  let pattern = new RegExp(regex)
  return pattern.test(str)
}

window.$swal = function (type, message, options = {}) {
  return Swal.fire({
    icon: type === 'success' ? 'success' : 'error',
    title: type === 'success' ? 'Great!' : 'Oops...',
    text: message,
    ...options,
  })
}

window.$showLoading = function (message = null) {
  Swal.fire({
    icon: 'info',
    title: 'Đang xử lý!',
    html: message ?? 'Không được tắt trang này, vui lòng đợi trong giây lát!',
    timerProgressBar: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowEnterKey: false,
    didOpen: () => {
      Swal.showLoading()
    },
    willClose: () => {},
  })
}

window.$hideLoading = function () {
  Swal.close()
}

window.$base64_decode = function (str) {
  // Going backwards: from bytestream, to percent-encoding, to original string.
  return decodeURIComponent(
    atob(str)
      .split('')
      .map(function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
      })
      .join('')
  )
}

window.$getCountryName = function (code) {
  return new Intl.DisplayNames(['en'], { type: 'region' }).of(
    code?.toUpperCase()
  )
}

window.$formDataToPayload = function (data) {
  const payload = {}
  for (let [key, value] of data.entries()) {
    payload[key] = value
  }
  return payload
}

// network checking
window.addEventListener('online', function (e) {
  console.log('online')
  $swal('success', 'Network is online')
})
window.addEventListener('offline', function (e) {
  console.log('offline')
  $swal('error', 'Network is offline')
})
// image error
function imgError(e) {
  let img = e.target
  img.removeEventListener('error', imgError)
  img.src = '/images/error-404.webp'
}

document.querySelectorAll('img').forEach((img) => {
  if (img.naturalWidth === 0) {
    img.addEventListener('error', imgError)
    img.src = img.src
  }
})

// copy function
var clipboard = new ClipboardJS('.copy')

clipboard.on('success', function (e) {
  $swal('success', 'Copied: ' + e.text)
})

clipboard.on('error', function (e) {
  $swal('error', 'Copy failed')
})
// variables
window.$userLevelName = function (level) {
  return level.charAt(0).toUpperCase() + level.slice(1)
}

// extra
window.$logout = async function () {
  try {
    const result = await axios.post('/logout')
  } finally {
    window.location.href = '/login'
  }
}
