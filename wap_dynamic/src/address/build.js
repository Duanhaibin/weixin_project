import ChinaAddressData from './china_address_v3.json'

var data = []
ChinaAddressData.forEach(function(t, i) {
  if(!t.parent) {
    data.push(t)
  }
})

var data2 = []
data.forEach(function(t) {
  ChinaAddressData.forEach(function(vt) {
    if(vt.parent == t.value) {
      data2.push(vt)
    }
  })
})


// 去掉辖区
ChinaAddressData.forEach(function(t, i) {
  if(t.name != '市辖区') {
    data.push(t)
  }
})

console.log(JSON.stringify(data.concat(data2)))
