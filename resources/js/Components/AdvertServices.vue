<script setup>
import { computed, ref } from 'vue';
import axios from 'axios';
import TooltipIcon from '@/Components/TooltipIcon.vue';
import { route } from 'ziggy-js';

const props = defineProps({
  advert: Object,
});
const selected = ref([]);
const services = ref([
  {
    key: 'highlight',
    title: 'Виокремлення',
    description: 'Виділення оголошення на 7 днів',
    tooltip:
      'Ваше оголошення буде виділено жовтим фоном на 7 днів. Вартість послуги – 25 грн. \n' +
      'Виділення оголошення - чудовий спосіб зробити Ваше оголошення яскравішим та помітнішим на тлі інших оголошень',
    price: 25,
  },
  {
    key: 'pin',
    title: 'Закріплення',
    description: 'Закріплення на початку списку',
    tooltip:
      'Ваше оголошення на вибрану кількість днів підніметься на початок списку оголошень схожої тематики і ' +
      'не опускатиметься вниз списку при додаванні нових безкоштовних оголошень, а протягом усього періоду дії послуги ' +
      'перебуватиме нагорі списку розділу, потрапляючи в поле зору відвідувачів сайту. Вартість послуги 15 грн на день',
    price: 15,
  },
  {
    key: 'urgent',
    title: 'Терміново',
    description: 'Позначка “Терміново” на 7 днів',
    tooltip:
      'Ваше оголошення буде позначено написом «Терміново» на період 7 днів. Вартість послуги – 25 грн \n' +
      '- Ви привертаєте більше уваги, а значить отримуєте більше відгуків \n' +
      '- Ваше оголошення стає більш помітним серед інших пропозицій',
    price: 25,
  },
  {
    key: 'premium',
    title: 'Преміум',
    description: 'На головній у блоці "Преміум"',
    tooltip:
      'Ваше оголошення на день закріплюється в блоці Преміум оголошення; на головній сторінці сайту. Вартість послуги 25 грн',
    price: 25,
  },
]);

const packages = ref([
  {
    key: 'turbo7',
    title: 'Пакет "Турбо 7"',
    description:
      'Це найбільш зручний варіант виділення оголошення на сайті на цілий тиждень. Вартість такого пакету послуг складає 49 грн. Він включає всі види просування на сайті:\n' +
      '- Закріплення оголошення на 7 днів\n' +
      '- Виділення оголошення на 7 днів\n' +
      '- Позначка "Терміново" на 7 днів\n' +
      '- Закріплення оголошення у блоці "Преміум оголошення" на головній сторінці сайту на 7 днів',
    price: 49,
    includes: ['highlight', 'pin', 'urgent', 'premium'],
  },
  {
    key: 'turbo30',
    title: 'Пакет "Турбо 30"',
    description:
      'Це найбільш зручний варіант виділення оголошення на сайті на цілий тиждень. Вартість такого пакету послуг складає 49 грн. Він включає всі види просування на сайті:\n' +
      '- Закріплення оголошення на 30 днів\n' +
      '- Виділення оголошення на 30 днів\n' +
      '- Позначка "Терміново" на 30 днів\n' +
      '- Закріплення оголошення у блоці "Преміум оголошення" на головній сторінці сайту на 30 днів',
    price: 149,
    includes: ['highlight', 'pin', 'urgent', 'premium'],
  },
  {
    key: 'max',
    title: 'Пакет "Максимальний" *"',
    description:
      'Це найбільш зручний варіант виділення оголошення на сайті цілий місяць. Вартість такого пакету послуг складає 149 грн. Він включає всі види просування на сайті:\n' +
      '- Закріплення оголошення на 30 днів\n' +
      '- Виділення оголошення на 30 днів\n' +
      '- Позначка "Терміново" на 30 днів\n' +
      '- Закріплення оголошення у блоці "Преміум оголошення" на головній сторінці сайту на 30 днів\n' +
      '- Розміщення посилання на оголошення в групах БАЗАРу у соціальних мережах Facebook, Viber, Instagram, Telegram, Pinterest, VK, OK (за наявності якісного зображення в оголошенні)',
    price: 199,
    includes: ['highlight', 'pin', 'urgent', 'premium'],
  },
]);

const selectedPackages = ref([]);
const selectedServices = ref([]);

const serviceMap = computed(() => Object.fromEntries(services.value.map((s) => [s.key, s])));

const isIncludedInPackage = (key) =>
  packages.value.some((p) => selectedPackages.value.includes(p.key) && p.includes.includes(key));

const handlePackageChange = (pack) => {
  const isSelected = selectedPackages.value.includes(pack.key);

  if (isSelected) {
    // Якщо вибрано — додаємо залежні послуги
    pack.includes.forEach((k) => {
      if (!selectedServices.value.includes(k)) {
        selectedServices.value.push(k);
      }
    });
  } else {
    // Якщо зняли — знімаємо залежні послуги
    pack.includes.forEach((k) => {
      const index = selectedServices.value.indexOf(k);
      if (index !== -1) selectedServices.value.splice(index, 1);
    });
  }
};

const totalPrice = computed(() => {
  const serviceKeysInPackages = new Set(
    packages.value.filter((p) => selectedPackages.value.includes(p.key)).flatMap((p) => p.includes)
  );

  const uniqueServices = selectedServices.value.filter((key) => !serviceKeysInPackages.has(key));

  const serviceSum = uniqueServices.reduce((sum, key) => {
    const service = serviceMap.value[key];
    return sum + (service?.price || 0);
  }, 0);

  const packageSum = selectedPackages.value.reduce((sum, key) => {
    const pack = packages.value.find((p) => p.key === key);
    return sum + (pack?.price || 0);
  }, 0);

  return serviceSum + packageSum;
});

const canSubmit = computed(
  () => selectedPackages.value.length > 0 || selectedServices.value.length > 0
);

const submit = async () => {
  console.log([...selectedPackages.value, ...selectedServices.value]);
  try {
    await axios.post(route('account.adverts.purchase', props.advert.id), {
      types: [...selectedPackages.value, ...selectedServices.value],
    });
    alert('Послуги активовано!');
    selectedPackages.value = [];
    selectedServices.value = [];
  } catch (e) {
    alert('Помилка покупки');
  }
};
</script>
<template>
  <div class="max-w-2xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold">
      Послуги просування
    </h2>

    <!-- 🧳 Пакети -->
    <div>
      <h3 class="text-lg font-semibold mb-2">
        Пакети
      </h3>
      <div class="flex flex-col gap-4">
        <div
          v-for="pack in packages"
          :key="pack.key"
          class="border-l-4 border-yellow-400 bg-yellow-50 p-4 rounded-md shadow"
        >
          <label class="flex items-start gap-3 cursor-pointer">
            <input
              v-model="selectedPackages"
              type="checkbox"
              class="form-checkbox h-5 w-5 mt-1 text-yellow-600"
              :value="pack.key"
              @change="handlePackageChange(pack)"
            >
            <div>
              <h4 class="font-bold text-lg">{{ pack.title }} — {{ pack.price }} грн</h4>
              <p class="text-sm text-gray-800 whitespace-pre-line">{{ pack.description }}</p>
              <ul class="list-disc ml-4 text-sm mt-2 text-gray-600">
                <li
                  v-for="service in pack.includes"
                  :key="service"
                >
                  {{ serviceMap[service]?.title || service }}
                </li>
              </ul>
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- 🧩 Окремі послуги -->
    <div>
      <h3 class="text-lg font-semibold mb-2">
        Окремі послуги
      </h3>
      <div class="flex flex-col gap-4">
        <div
          v-for="service in services"
          :key="service.key"
          class="border p-4 rounded-md shadow-sm"
        >
          <label class="flex items-start gap-3 cursor-pointer">
            <input
              v-model="selectedServices"
              type="checkbox"
              class="form-checkbox mt-1 h-5 w-5 text-blue-600"
              :value="service.key"
              :disabled="isIncludedInPackage(service.key)"
            >
            <div>
              <div class="flex items-center gap-2">
                <h4 class="font-semibold text-base">{{ service.title }}</h4>
                <TooltipIcon :message="service.tooltip" />
              </div>
              <p class="text-sm text-gray-600">{{ service.description }}</p>
              <p class="font-bold mt-1 text-sm">💰 {{ service.price }} грн</p>
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- ✅ Підсумок -->
    <div class="flex justify-between items-center pt-4">
      <p class="text-lg font-semibold">
        Обрано: {{ selectedPackages.length + selectedServices.length }} | Сума: {{ totalPrice }} грн
      </p>
      <button
        :disabled="!canSubmit"
        class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 disabled:opacity-50"
        @click="submit"
      >
        Купити вибране
      </button>
    </div>
  </div>
</template>
