<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';

const { t } = useI18n();
const user = usePage().props.auth.user;

const form = useForm({
    phone: user.phone,
});

// Toggle states for additional features
const notificationsEnabled = ref(true);
const twoFactorAuth = ref(false);
const marketingConsent = ref(false);

const updatePhone = () => {
    form.patch(route('cabinet.profile.phone.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <p class="mt-5 text text-gray-600 dark:text-gray-400">
                {{ t("Update your notification information.") }}
            </p>
        </header>

        <div class=" space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ t('Enable notifications') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ t('Receive SMS notifications') }}
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="notificationsEnabled = !notificationsEnabled"
                    :class="[
            notificationsEnabled ? 'bg-indigo-600' : 'bg-gray-200',
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2'
          ]"
                    role="switch"
                    :aria-checked="notificationsEnabled"
                >
          <span
              :class="[
              notificationsEnabled ? 'translate-x-5' : 'translate-x-0',
              'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
            ]"
          />
                </button>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ t('Two-factor authentication') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ t('Add extra security to your account') }}
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="twoFactorAuth = !twoFactorAuth"
                    :class="[
            twoFactorAuth ? 'bg-indigo-600' : 'bg-gray-200',
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2'
          ]"
                    role="switch"
                    :aria-checked="twoFactorAuth"
                >
          <span
              :class="[
              twoFactorAuth ? 'translate-x-5' : 'translate-x-0',
              'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
            ]"
          />
                </button>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ t('Marketing consent') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ t('Receive promotional offers') }}
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="marketingConsent = !marketingConsent"
                    :class="[
            marketingConsent ? 'bg-indigo-600' : 'bg-gray-200',
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2'
          ]"
                    role="switch"
                    :aria-checked="marketingConsent"
                >
          <span
              :class="[
              marketingConsent ? 'translate-x-5' : 'translate-x-0',
              'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
            ]"
          />
                </button>
            </div>
        </div>
    </section>
</template>
