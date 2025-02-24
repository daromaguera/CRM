import { ref } from 'vue'

export function useInvoiceData() {
  const invoiceData = ref([])

  const generateRandomInvoiceData = (count = 20) => {
    const data = []
    for (let i = 0; i < count; i++) {
      const date = new Date()
      date.setDate(date.getDate() - i)
      const formattedDate = date.toLocaleDateString('en-US')
      const time = `${Math.floor(Math.random() * 12) + 1}:${Math.floor(Math.random() * 60)
        .toString()
        .padStart(2, '0')}${Math.random() > 0.5 ? 'am' : 'pm'}`
      const invoiceNo = `ZEX${Math.floor(Math.random() * 1000000)
        .toString()
        .padStart(6, '0')}`
      const amount = `$${(Math.random() * 1000000).toFixed(2)}`

      data.push({
        date: formattedDate,
        time,
        INo: invoiceNo,
        amount,
      })
    }
    invoiceData.value = data
  }

  return { invoiceData, generateRandomInvoiceData }
}
