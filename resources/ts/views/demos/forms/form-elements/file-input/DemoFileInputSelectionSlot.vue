<script lang="ts" setup>
const files = ref<File[]>([])
</script>

<template>
  <AppFileInput
    v-model="files"
    multiple
    placeholder="Upload your documents"
    label="File input"
    prepend-inner-icon="bx-paperclip"
  >
    <template #selection="{ fileNames }">
      <template
        v-for="fileName in fileNames"
        :key="fileName"
      >
        <VChip
          label
          size="small"
          color="primary"
          class="me-2"
        >
          {{ fileName }}
        </VChip>
      </template>
    </template>
  </AppFileInput>
</template>
