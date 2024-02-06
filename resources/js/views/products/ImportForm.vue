<template>
  <div
    id="user"
    class="modal fade"
    :class="{ 'show d-block': isShow }"
  >
    <div
      class="modal-dialog modal-dialog-centered"
      role="document"
    >
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Import user
          </h5>
        </div>
        <form
          class="form"
          @submit.prevent="saveData"
        >
          <div class="modal-body">
            <div class="mb-3">
              <input
                class="form-control"
                type="file"
                placeholder-input="Pilih file"
                :required="true"
                @change="onFileChoosed"
              >
              <p class="pt-1">
                Download format file
                <button
                  type="button"
                  class="btn-text btn p-0 text-info cursor-pointer"
                  @click.prevent="downloadFormat"
                >
                  di sini!
                </button>
              </p>
            </div>
            <div class="col mt-4 text-end">
              <button
                class="btn btn-label-secondary me-2"
                type="button"
                @click.prevent="$emit('toggleModal')"
              >
                Batal
              </button>
              <button
                type="submit"
                class="btn btn-primary"
                :disabled="loadingSubmit"
              >
                <div
                  v-if="loadingSubmit"
                  class="spinner-border text-white me-2"
                  role="status"
                >
                  <span class="visually-hidden">Loading...</span>
                </div>
                Simpan
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div
    v-if="isShow"
    class="modal-backdrop fade show"
  />
</template>

<script setup>
import { apiPost } from '@services/httpApi.js'
import paths from '@services/paths.js'
import Swal from 'sweetalert2'
import { ref } from 'vue'

const props = defineProps({ isShow: Boolean })
const emitEvents = defineEmits(['toggleModal', 'setAlert'])
const file = ref(null)

const loadingSubmit = ref(false)

const onFileChoosed = event => {
  file.value = event.target.files[0]
  console.log(file.value)
}

const saveData = async () => {
  try {
    loadingSubmit.value = true

    const formData = new FormData()

    formData.append('file', file.value)

    const res = await apiPost(paths.importUsers, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    emitEvents('setAlert', { isShow: true, type: 'primary', msg: res.message })
    emitEvents('toggleModal')
  } catch (e) {
    Swal.fire(e.message, '', 'error')
  } finally {
    loadingSubmit.value = false
  }
}

const downloadFormat = async () => {
  try {
    loadingSubmit.value = true

    const res = await api.getAll(paths.downloadFormat, {
      responseType: 'blob',
    })

    const url = window.URL.createObjectURL(new Blob([res]))
    const link = document.createElement('a')

    link.href = url
    link.setAttribute('download', 'format-upload-user.xlsx')
    document.body.appendChild(link)
    link.click()
    window.URL.revokeObjectURL(url)
  } catch (e) {
    Swal.fire(e.message, '', 'error')
  } finally {
    loadingSubmit.value = false
  }
}
</script>
