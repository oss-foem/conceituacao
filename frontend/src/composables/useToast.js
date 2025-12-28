import { toast } from 'vue3-toastify';

export const useToast = () => {
  const notifyError = (message, options = {}) => {
    toast.error(message, {
      autoClose: 3000,
      position: toast.POSITION.BOTTOM_RIGHT,
      ...options,
    });
  };

  const notifySuccess = (message, options = {}) => {
    toast.success(message, {
      autoClose: 3000,
      position: toast.POSITION.BOTTOM_RIGHT,
      ...options,
    });
  };

  const notifyInfo = (message, options = {}) => {
    toast.info(message, {
      autoClose: false, 
      position: toast.POSITION.BOTTOM_RIGHT,
      closeButton: true,
      ...options,
    });
  };

  return { notifyError, notifySuccess, notifyInfo };
};