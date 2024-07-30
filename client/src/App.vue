<template>
  <router-view />
  <teleport to="body">
    <NotificationGroup group="main">
      <div
        class="pointer-events-none fixed inset-0 z-[1070] flex items-end justify-end p-6 px-4 py-6"
      >
        <div class="w-full max-w-sm">
          <Notification
            v-slot="{ notifications }"
            enter="transform ease-out duration-300 transition"
            enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
            enter-to="translate-y-0 opacity-100 sm:translate-x-0"
            leave="transition ease-in duration-500"
            leave-from="opacity-100"
            leave-to="opacity-0"
            move="transition duration-500"
            move-delay="delay-300"
          >
            <div v-for="notification in notifications" :key="notification.id">
              <div
                class="mx-auto mt-4 flex w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-md"
              >
                <div
                  v-if="!!notification.type"
                  class="flex w-12 items-center justify-center"
                  :class="[
                    notification.type === 'info' && 'bg-primary text-white',
                    notification.type === 'warning' && 'bg-warning text-white',
                    notification.type === 'positive' &&
                      'bg-positive text-white',
                    notification.type === 'negative' &&
                      'bg-negative text-white',
                  ]"
                >
                  <TIcon
                    v-if="notification.type == 'info'"
                    name="info"
                    size="lg"
                  />
                  <TIcon
                    v-if="notification.type == 'warning'"
                    name="warning"
                    size="lg"
                  />
                  <TIcon
                    v-if="notification.type == 'positive'"
                    name="task_alt"
                    size="lg"
                  />
                  <TIcon
                    v-if="notification.type == 'negative'"
                    name="error"
                    size="lg"
                  />
                </div>

                <div class="-mx-3 px-4 py-2">
                  <div class="mx-3">
                    <span
                      class="font-semibold font-sans"
                      :class="[
                        notification.type === 'info' && 'text-primary',
                        notification.type === 'warning' && 'text-warning',
                        notification.type === 'positive' && 'text-positive',
                        notification.type === 'negative' && 'text-negative',
                      ]"
                    >
                      {{ notification.title }}
                    </span>
                    <p class="text-sm text-gray-600 font-sans">
                      {{ notification.text }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </Notification>
        </div>
      </div>
    </NotificationGroup>
  </teleport>
</template>
