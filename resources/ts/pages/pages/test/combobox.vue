<template>
  <!-- 👉 combo box -->

  <!-- 👉 combo box variants -->
  <VRow>
    <VCol cols="12">
      <VCard title="variants">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox density -->
    <VCol cols="12">
      <VCard title="density">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="comfortable"
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="compact"
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox placeholder  -->
    <VCol cols="12">
      <VCard title="placeholder">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <p>last example has persistent placeholder</p>
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                placeholder="select city"
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                placeholder="select city"
                density="comfortable"
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                placeholder="select city"
                density="compact"
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox multiple  -->
    <VCol cols="12">
      <VCard title="Multiple">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                class="my-5"
                multiple
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="comfortable"
                multiple
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="compact"
                multiple
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox input chips  -->
    <VCol cols="12">
      <VCard title="chips">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                class="my-5"
                chips
                multiple
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="comfortable"
                multiple
                chips
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="compact"
                multiple
                chips
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox closable chips  -->
    <VCol cols="12">
      <VCard title="closable chips">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                class="my-5"
                chips
                closable-chips
                multiple
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="comfortable"
                multiple
                chips
                closable-chips
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="compact"
                multiple
                chips
                closable-chips
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox closable chips with clearable  -->
    <VCol cols="12">
      <VCard title="closable chips with clearable">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                class="my-5"
                chips
                closable-chips
                multiple
                clearable
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="comfortable"
                multiple
                chips
                closable-chips
                clearable
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="compact"
                multiple
                chips
                closable-chips
                clearable
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox selection slot chips  -->
    <VCol cols="12">
      <VCard title="selection with slot chips">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                label="City"
                multiple
                density="default"
                class="my-5"
              >
                <template #selection="data">
                  <VChip
                    :key="JSON.stringify(data.item)"
                    v-bind="data.attrs"
                    :model-value="data.selected"
                    :disabled="data.disabled"
                    size="small"
                    @click:close="data.parent.selectItem(data.item)"
                  >
                    <template #prepend>
                      <VAvatar
                        class="bg-primary text-uppercase"
                        start
                      >
                        {{ data.item.title.slice(0, 1) }}
                      </VAvatar>
                    </template>
                    {{ data.item.title }}
                  </VChip>
                </template>
              </AppCombobox>

              <AppCombobox
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                label="City"
                multiple
                density="default"
                class="my-5"
              >
                <template #selection="data">
                  <VChip
                    :key="JSON.stringify(data.item)"
                    v-bind="data.attrs"
                    :model-value="data.selected"
                    :disabled="data.disabled"
                    size="small"
                    @click:close="data.parent.selectItem(data.item)"
                  >
                    <template #prepend>
                      <VAvatar
                        class="bg-primary text-uppercase"
                        start
                      >
                        {{ data.item.title.slice(0, 1) }}
                      </VAvatar>
                    </template>
                    {{ data.item.title }}
                  </VChip>
                </template>
              </AppCombobox>

              <AppCombobox
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                label="City"
                multiple
                density="comfortable"
                class="my-5"
              >
                <template #selection="data">
                  <VChip
                    :key="JSON.stringify(data.item)"
                    v-bind="data.attrs"
                    :model-value="data.selected"
                    :disabled="data.disabled"
                    size="small"
                    @click:close="data.parent.selectItem(data.item)"
                  >
                    <template #prepend>
                      <VAvatar
                        class="bg-primary text-uppercase"
                        start
                      >
                        {{ data.item.title.slice(0, 1) }}
                      </VAvatar>
                    </template>
                    {{ data.item.title }}
                  </VChip>
                </template>
              </AppCombobox>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox with prepend inner icons  -->
    <VCol cols="12">
      <VCard title="prepend innner icons">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                class="my-5"
                prepend-inner-icon="bx-building"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="comfortable"
                prepend-inner-icon="bx-building"
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="compact"
                prepend-inner-icon="bx-building"
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox with prepend icons  -->
    <VCol cols="12">
      <VCard title="prepend icons">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                class="my-5"
                prepend-icon="bx-building"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="comfortable"
                prepend-icon="bx-building"
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="compact"
                prepend-icon="bx-building"
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 combobox loading  -->
    <VCol cols="12">
      <VCard title="loading">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                density="default"
                placeholder="select city"
                loading
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                placeholder="select city"
                density="comfortable"
                loading
                class="my-5"
              />

              <AppCombobox
                label="City"
                :items="['Delhi', 'Mumbai', 'Ahemedabad', 'Bangalore', 'Jaipur']"
                placeholder="select city"
                loading
                density="compact"
                class="my-5"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
