<script setup>
import { blogService } from "@services"

const blogs = ref([])

onMounted(async () => {
  try {
    const res = await blogService.getBlogs()

    blogs.value = res.data
  } catch (e) {
    console.error(e.message)
  }
})
</script>

<template>
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
            height="100"
          >
        </td>
        <td
          class="text-center"
          v-html="item.desc"
        />
        <td class="text-center">
          {{ item.created_at }}
        </td>
      </tr>
    </tbody>
  </VTable>
</template>
