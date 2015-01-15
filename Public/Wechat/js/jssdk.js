wx.ready(function () {
  var shareData = {
      title: '果大侠-办公室水果第一品牌',
      desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
      link: 'http://mp.weixin.qq.com/s?__biz=MzAwODAzMTAwMg==&mid=200805974&idx=1&sn=36f55c2d0a9a9051bba2065f3ec4ce4b#rd',
      imgUrl: 'http://www.meirixianguo.com/Public/Wechat/images/logo_left.png',
      trigger: function (res) {
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
      },
      fail: function (res) {
      }
  };
  wx.onMenuShareAppMessage(shareData);
  wx.onMenuShareTimeline(shareData);
  //wx.onMenuShareQQ(shareData);
  //wx.onMenuShareWeibo(shareData);
});

wx.error(function (res) {
  alert(res.errMsg);
});
