export function getStatusText(status) {
  const map = {
    pending: 'Очікує',
    processing: 'В процесі',
    completed: 'Виконано',
    cancelled: 'Скасовано',
    approved: 'Схвалено',
    rejected: 'Відхилено',
  };
  return map[status] || status;
}

export const getStatusClass = (status) => {
  const classes = {
    paid: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    failed: 'bg-red-100 text-red-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

export const getAdvertStatusClass = (status) => {
  const classes = {
    active: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    rejected: 'bg-red-100 text-red-800',
    expired: 'bg-gray-100 text-gray-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

export const orderStatusColors = {
  paid: '#4ac425',
  pending: '#F59E0B',
  failed: '#EF4444',
};

export const advertStatusColors = {
  active: '#4ac425',
  moderation: '#346eeb',
  pending: '#F59E0B',
  rejected: '#EF4444',
  closed: '#317a1b',
  expired: '#6B7280',
};
