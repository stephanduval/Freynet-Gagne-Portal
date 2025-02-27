<script setup lang="ts">
import asana from '@images/icons/brands/asana.png'
import behance from '@images/icons/brands/behance.png'
import dribbble from '@images/icons/brands/dribbble.png'
import facebook from '@images/icons/brands/facebook.png'
import github from '@images/icons/brands/github.png'
import google from '@images/icons/brands/google.png'
import linkedin from '@images/icons/brands/linkedin.png'
import mailchimp from '@images/icons/brands/mailchimp.png'
import slack from '@images/icons/brands/slack.png'
import twitter from '@images/icons/brands/twitter.png'

const connectedAccounts = ref([
  {
    logo: google,
    name: 'Google',
    subtitle: 'Calendar and contacts',
    connected: true,
  },
  {
    logo: slack,
    name: 'Slack',
    subtitle: 'Communication',
    connected: false,
  },
  {
    logo: github,
    name: 'GitHub',
    subtitle: 'Manage your Git repositories',
    connected: true,
  },
  {
    logo: mailchimp,
    name: 'MailChimp',
    color: 'yellow',
    subtitle: 'Email marketing service',
    connected: true,
  },
  {
    logo: asana,
    name: 'Asana',
    subtitle: 'Task management',
    connected: false,
  },

])

const socialAccounts = ref([
  {
    logo: facebook,
    name: 'Facebook',
    connected: false,
  },
  {
    logo: twitter,
    name: 'Twitter',
    links: { username: '@Themeselection', link: 'https://twitter.com/Theme_Selection' },
    connected: true,
  },
  {
    logo: linkedin,
    name: 'LinkedIn',
    links: { username: '@Themeselection', link: 'https://in.linkedin.com/company/themeselection' },
    connected: true,
  },
  {
    logo: dribbble,
    name: 'Dribbble',
    connected: false,

  },
  {
    logo: behance,
    name: 'Behance',
    connected: false,
  },
])
</script>

<template>
  <VCard>
    <VRow>
      <VCol
        cols="12"
        md="6"
        class="pe-md-0 pb-0 pb-md-3"
      >
        <!-- 👉 Connected Accounts -->
        <VCard flat>
          <VCardItem>
            <VCardTitle class="mb-1">
              Connected Accounts
            </VCardTitle>
            <VCardSubtitle>
              Display content from your connected accounts on your site
            </VCardSubtitle>
          </VCardItem>
          <VCardText>
            <VList class="card-list">
              <VListItem
                v-for="item in connectedAccounts"
                :key="item.logo"
              >
                <template #prepend>
                  <VAvatar
                    :image="item.logo"
                    size="32"
                    class="me-1"
                    :rounded="0"
                  />
                </template>
                <VListItemTitle>
                  <h6 class="text-h6">
                    {{ item.name }}
                  </h6>
                </VListItemTitle>
                <VListItemSubtitle class="text-xs">
                  {{ item.subtitle }}
                </VListItemSubtitle>
                <template #append>
                  <VListItemAction>
                    <VSwitch
                      v-model="item.connected"
                      density="compact"
                      class="me-1"
                    />
                  </VListItemAction>
                </template>
              </VListItem>
            </VList>
          </VCardText>
        </VCard>
      </VCol>
      <VCol
        cols="12"
        md="6"
        class="ps-md-0 pt-0 pt-md-3"
      >
        <!-- 👉 Social Accounts -->
        <VCard flat>
          <VCardItem>
            <VCardTitle class="mb-1">
              Social Accounts
            </VCardTitle>
            <VCardSubtitle>
              Display content from social accounts on your site
            </VCardSubtitle>
          </VCardItem>
          <VCardText>
            <VList class="card-list">
              <VListItem
                v-for="item in socialAccounts"
                :key="item.logo"
              >
                <template #prepend>
                  <VAvatar
                    :image="item.logo"
                    size="32"
                    class="me-1"
                    :rounded="0"
                  />
                </template>
                <VListItemTitle>
                  <h6 class="text-h6">
                    {{ item.name }}
                  </h6>
                </VListItemTitle>
                <VListItemSubtitle v-if="item.links?.link">
                  <a
                    :href="item.links.link"
                    target="_blank"
                    rel="noopener noreferrer"
                  >{{ item.links?.username }}</a>
                </VListItemSubtitle>
                <VListItemSubtitle
                  v-else
                  class="text-xs"
                >
                  Not Connected
                </VListItemSubtitle>
                <template #append>
                  <VListItemAction>
                    <IconBtn
                      variant="tonal"
                      :color="item.connected ? 'error' : 'secondary'"
                      class="rounded"
                    >
                      <VIcon
                        size="22"
                        :icon="item.connected ? 'bx-trash' : 'bx-link' "
                      />
                    </IconBtn>
                  </VListItemAction>
                </template>
              </VListItem>
            </VList>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </VCard>
</template>

<style lang="scss">
.card-list {
  --v-card-list-gap: 1rem;
}
</style>
