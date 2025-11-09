// Use the full aio build which includes both Notify and Confirm
import { Notify, Confirm, Notiflix} from 'notiflix/build/notiflix-aio'

import 'notiflix/dist/notiflix-3.2.8.min.css'

Notify.init({
  position: 'right-top',
  timeout: 3000,
  width: '300px',
  fontSize: '15px',
  clickToClose: true,
  success: {
    background: '#32c682',
    textColor: '#ffffff',
  },
  failure: {
    background: '#f44336',
    textColor: '#ffffff',
  },
  warning: {
    background: '#ff9800',
    textColor: '#ffffff',
  },
  info: {
    background: '#2196f3',
    textColor: '#ffffff',
  },
})

// Configure Confirm dialog styling (make the OK/delete button red)
Confirm.init({
  okButtonBackground: '#dc3545', // bootstrap danger
  okButtonColor: '#ffffff',
  cancelButtonBackground: '#6c757d',
  cancelButtonColor: '#ffffff',
})


/**
 * Displays a success notification.
 * @param {string} message - The message to display.
 */
export function showSuccess(message) {
  Notify.success(message)
}

/**
 * Displays a failure/error notification.
 * @param {string} message - The message to display.
 */
export function showError(message) {
  Notify.failure(message)
}

/**
 * Displays a warning notification.
 * @param {string} message - The message to display.
 */
export function showWarning(message) {
  Notify.warning(message)
}

/**
 * Displays an info notification.
 * @param {string} message - The message to display.
 */
export function showInfo(message) {
  Notify.info(message)
}

/**
 * Displays a confirmation modal before proceeding with an action.
 * * @param {string} title - The title of the confirmation box.
 * @param {string} message - The message asking for confirmation.
 * @param {function} onYes - Callback function executed if the user clicks 'Yes'.
 * @param {function} [onNo] - Optional callback function executed if the user clicks 'No'.
 */
export function showConfirm(title, message, onYes, onNo = () => {}) {
  Confirm.show(
    title,
    message,
    'Yes, delete', // OK button text
    'Cancel', // Cancel button text
    onYes, // Execute this function if Yes is clicked
    onNo, // Execute this function if No is clicked
  )
}
