import newAccount from '@images/misc/new-account.png'
import changeInfo from '@images/misc/profile_change.png'
import axios from 'axios'

export function useBroadcastEvent() {
  const broadcastEvent = async (userId, message) => {
    try {
      await axios.post('/api/broadcast-event', { userId, message })
      await axios.post('/api/save-notification', {
        id: Date.now(),
        title: 'Information Change',
        subtitle: message,
        time: new Date().toLocaleTimeString(),
        isSeen: false,
        img: changeInfo,
        user_id: userId,
      })
    } catch (error) {
      console.error('Failed to broadcast event:', error)
    }
  }

  const broadcastPublicEvent = async (message) => {
    try {
      await axios.post('/api/public-notification', {
        title: 'Public Notification',
        subtitle: message,
        time: new Date().toLocaleTimeString(),
        isSeen: false,
        img: newAccount,
      })
    } catch (error) {
      console.error('Failed to broadcast public event:', error)
    }
  }

  return {
    broadcastEvent,
    broadcastPublicEvent,
  }
}
