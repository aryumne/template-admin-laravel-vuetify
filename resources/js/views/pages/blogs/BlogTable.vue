<script setup>
import { blogService } from "@services"
import { snackbarStore } from '@stores'

const blogs = ref([])
const blogId = ref(null)
const dialog = ref(false)

const deleteConfirm = async id => {
  blogId.value = id
  dialog.value = true
}

const deleteBlog = async() => {
  try {

    const res = await blogService.deleteBlog(blogId.value)

    snackbarStore.setMsg(res.message)
    await getData()
    dialog.value = false
  } catch (e) {
    snackbarStore.setMsg(res.message)
  }
}

const getData = async () => {
  try {
    const res = await blogService.getBlogs()

    blogs.value = res.data
  } catch (e) {
    snackbarStore.setMsg(res.message)
  }
}

onMounted(getData)
</script>

<template>
  <VDialog
    v-model="dialog"
    width="auto"
  >
    <VCard>
      <VCardText>
        Are you sure to delete this blog?
      </VCardText>
      <VCardActions>
        <VBtn
          color="secondary"
          variant="tonal"
          
          @click="dialog = false"
        >
          Close Dialog
        </VBtn>
        <VBtn
          color="error"
          variant="flat"
          @click.prevent="deleteBlog"
        >
          Delete
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
  <VTable>
    <thead>
      <tr>
        <th class="text-uppercase">
          Blog type
        </th>
        <th>
          title
        </th>
        <th>
          thumb
        </th>
        <th>
          recomended
        </th>
        <th>
          created_at
        </th>
        <th>
          Action
        </th>
      </tr>
    </thead>

    <tbody>
      <tr
        v-for="item in blogs"
        :key="item.id"
      >
        <td>
          {{ item.blog_type_name }}
        </td>
        <td class="text-center">
          {{ item.title }}
        </td>
        <td class="text-center">
          <img
            :src="item.thumb_url"
            height="50"
          >
        </td>
        <td class="text-center">
          {{ item.is_recomended ? "Yes" : "No" }}
        </td>
        <td class="text-center">
          {{ item.created_at }}
        </td>
        <td class="text-center">
          <VTooltip location="top">
            <template #activator="{ props }">
              <RouterLink :to="{name: 'editBlog', params: {id: item.id}}">
                <VBtn
                  icon
                  v-bind="props"
                  color="warning"
                  size="x-small"
                  class="me-1"
                >
                  <VIcon color="grey-lighten-1">
                    mdi-open-in-new
                  </VIcon>
                </VBtn>
              </RouterLink>
            </template>
            <span>Edit</span>
          </VTooltip>
          <VTooltip location="top">
            <template #activator="{ props }">
              <VBtn
                icon
                v-bind="props"
                color="error"
                size="x-small"
                class="me-1"
                @click.prevent="deleteConfirm(item.id)"
              >
                <VIcon color="grey-lighten-1">
                  mdi-trash-can
                </VIcon>
              </VBtn>
            </template>
            <span>Delete</span>
          </VTooltip>
        </td>
      </tr>
    </tbody>
  </VTable>
</template>
