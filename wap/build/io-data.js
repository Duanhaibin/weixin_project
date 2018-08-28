/**
 * 模拟接口
 * @param api
 */
module.exports = function(api) {

  api.post('/', function(req, res) {
    res.send({
      result_code: "0000",
      result_msg: "获取成功",
      result_data: {
        appId: "wx9ed8167beb9897a7",
        nonceStr: "h9RHGqJJztcznZ3z",
        timestamp: 1523695488,
        url: "http://weixin.12301e.com/index.php?s=/w18/CbsBusiness/CbsBusinessWap/detail&from=singlemessage#/s/cbs123456",
        signature: "0ea9aa64bd79afce7e039065a29483481b20e89f",
        rawString: "jsapi_ticket=sM4AOVdWfPE4DxkXGEs8VNM9tgzqe5PKrQ3M66nb6v_du5n3b9GIZ8xeG9MtTBmMOb0ynNteEKdmDhIeIigOjQ&noncestr=h9RHGqJJztcznZ3z×tamp=1523695488&url=http://weixin.12301e.com/index.php?s=/w18/CbsBusiness/CbsBusinessWap/detail&from=singlemessage#/s/cbs123456"
      }
    })
  })

}
