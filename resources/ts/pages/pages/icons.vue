<script lang="ts" setup>
const iconsList = [
  'bx-dish',
  'bx-gift',
  'bx-log-out',
  'bx-heart',
  'bx-radio',
  'bx-microphone',
  'bx-palette',
  'bx-diamond',
  'bx-gas-pump',
  'bx-envelope',
  'bx-code',
  'bx-chair',
  'bx-key',
  'bx-bell',
  'bx-cycling',
  'bx-bxs-flask',
  'bx-phone',
  'bx-compass',
  'bx-note',
  'bx-laptop',
  'bx-error',
  'bx-fridge',
  'bx-link',
  'bx-first-aid',
  'bx-calendar',
  'bx-pencil',
  'bx-id-card',
  'bx-basket',
  'bx-cake',
  'bx-camera',
  'bx-music',
  'bx-moon',
  'bx-leaf',
  'bx-minus',
  'bx-bulb',
  'bx-log-in',
  'bx-no-entry',
  'bx-folder',
  'bx-cctv',
  'bx-play',
  'bx-grid',
  'bx-car',
  'bx-brush',
  'bx-home',
  'bx-bookmark',
  'bx-paper-plane',
  'bx-news',
  'bx-headphone',
  'bx-mobile',
  'bx-briefcase',
  'bx-directions',
  'bx-bulb',
  'bx-cart',
  'bx-dumbbell',
  'bx-happy',
  'bx-badge-check',
  'bx-wine',
  'bx-crop',
  'bx-football',
  'bx-abacus',
  'bx-network-chart',
  'bx-archive',
  'bx-exit',
  'bx-cut',
  'bx-list-plus',
  'bx-pulse',
  'bx-cube',
  'bx-battery',
  'bx-qr',
  'bx-arrow-back',
  'bx-cloud',
  'bx-library',
  'bx-download',
  'bx-terminal',
  'bx-arch',
  'bx-paint',
  'bx-wrench',
  'bx-help-circle',
  'bx-train',
  'bx-radar',
  'bx-trash',
  'bx-candles',
  'bx-receipt',
  'bx-clipboard',
  'bx-shield',
  'bx-book',
  'bx-edit',
  'bx-pointer',
  'bx-desktop',
  'bx-zoom-in',
  'bx-package',
  'bx-envelope-open',
  'bx-coffee',
  'bx-filter',
  'bx-wifi',
  'bx-game',
  'bx-globe',
  'bx-star',
  'bx-low-vision',
  'bx-line-chart',
  'bx-brightness',
  'bx-map',
  'bx-bolt-circle',
  'bx-run',
  'bx-cog',
  'bx-sun',
  'bx-bxs-watch',
  'bx-video',
  'bx-medal',
  'bx-chat',
  'bx-envelope-open',
  'bx-meteor',
  'bx-chalkboard',
  'bx-file',
  'bx-crown',
  'bx-wink-smile',
  'bx-tennis-ball',
  'bx-face',
  'bx-plug',
  'bx-arch',
  'bx-cookie',
  'bx-knife',
  'bx-pie-chart-alt-2',
  'bx-bulb',
  'bx-user',
  'bx-x',
  'bx-band-aid',
  'bx-coffee-togo',
  'bx-time',
  'bx-planet',
  'bx-cut',
  'bx-world',
  'bx-check-double',
  'bx-bone',
]
</script>

<template>
  <div>
    <div class="d-flex align-center flex-wrap">
      <VCard
        v-for="icon in iconsList"
        :key="icon"
        class="mb-6 me-6"
      >
        <VCardText class="py-3 px-4">
          <VIcon
            size="30"
            :icon="icon"
          />
        </VCardText>

        <!-- tooltips -->
        <VTooltip
          location="top"
          activator="parent"
        >
          {{ icon }}
        </VTooltip>
      </VCard>
    </div>

    <!-- more icons -->
    <div class="text-center">
      <VBtn
        href="https://boxicons.com/"
        rel="noopener noreferrer"
        color="primary"
        target="_blank"
      >
        View All Boxicons Icons
      </VBtn>
    </div>
  </div>
</template>
