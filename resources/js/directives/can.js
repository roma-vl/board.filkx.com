import { usePage } from '@inertiajs/vue3';

export default {
  mounted(el, binding) {
    const page = usePage();

    const can = page.props?.can || [];
    const permissions = page.props?.auth?.permissions || [];

    const allPermissions = [...new Set([...can, ...permissions])];

    const value = binding.value;

    const isAllowed = Array.isArray(value)
      ? value.some((permission) => allPermissions.includes(permission))
      : allPermissions.includes(value);

    if (!isAllowed) {
      el.remove();
    }
  },
};
