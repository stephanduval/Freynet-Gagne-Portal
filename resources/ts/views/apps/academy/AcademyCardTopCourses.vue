<script setup lang="ts">
const coursesData = [
  { title: 'Videography Basic Design Course', views: '1.2k', icon: 'bx-video', color: 'primary' },
  { title: 'Basic Front-end Development Course', views: '834', icon: 'bx-code-alt', color: 'info' },
  { title: 'Basic Fundamentals of Photography', views: '3.7k', icon: 'bx-camera', color: 'success' },
  { title: 'Advance Dribble Base Visual Design', views: '2.5k', icon: 'bx-basketball', color: 'warning' },
  { title: 'Your First Singing Lesson', views: '948', icon: 'bx-microphone', color: 'error' },
]

const moreList = [
  { title: 'Refresh', value: 'refresh' },
  { title: 'Update', value: 'update' },
  { title: 'Share', value: 'share' },
]
</script>

<template>
  <VCard>
    <VCardItem title="Top Courses">
      <template #append>
        <MoreBtn :menu-list="moreList" />
      </template>
    </VCardItem>

    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="(course, index) in coursesData"
          :key="index"
        >
          <template #prepend>
            <VAvatar
              rounded
              variant="tonal"
              size="40"
              class="me-1"
              :color="course.color"
            >
              <VIcon
                :icon="course.icon"
                size="24"
              />
            </VAvatar>
          </template>

          <template #title>
            <div class="text-h6 clamp-text text-wrap me-4">
              {{ course.title }}
            </div>
          </template>

          <template #append>
            <VChip
              variant="tonal"
              color="secondary"
              size="small"
            >
              {{ course.views }} Views
            </VChip>
          </template>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 24px;
}
</style>
