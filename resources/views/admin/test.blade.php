
@extends('admin.layouts.layout')

@section('content')
<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg hidden md:block">
      <div class="p-6 font-semibold text-lg">Tài khoản</div>
      <nav class="space-y-1">
        <a href="#" class="flex items-center px-6 py-2 text-gray-700 hover:bg-gray-100">
          🛒 <span class="ml-3">Lịch sử đơn hàng</span>
        </a>
        <a href="#" class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100">
          💳 <span class="ml-3">Lịch sử giao dịch</span>
        </a>
        <a href="#" class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100">
          🔐 <span class="ml-3">Mật khẩu & bảo mật</span>
        </a>
        <a href="#" class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100">
          💬 <span class="ml-3">Bình luận của tôi</span>
        </a>
        <a href="#" class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100">
          ❤️ <span class="ml-3">Sản phẩm yêu thích</span>
        </a>
        <a href="#" class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100">
          🔗 <span class="ml-3">Giới thiệu bạn bè</span>
        </a>
      </nav>
    </aside>
  
    <!-- Main content -->
    <main class="flex-1 p-6">
      <div class="bg-white p-6 rounded-xl shadow">
        <h1 class="text-2xl font-semibold mb-2">Lịch sử đơn hàng</h1>
        <p class="text-gray-500 mb-6">Hiển thị thông tin các sản phẩm bạn đã mua tại Divine Shop</p>
  
        <!-- Bộ lọc -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-5">
          <input type="text" placeholder="Mã đơn hàng" class="border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500">
          <input type="number" placeholder="Số tiền từ" class="border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500">
          <input type="number" placeholder="Số tiền đến" class="border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500">
          <input type="date" class="border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500">
          <input type="date" class="border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500">
          <button class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-4 py-2 text-sm">Lọc</button>
        </div>
  
        <!-- Bảng -->
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
              <tr>
                <th class="px-4 py-2 border">Thời gian</th>
                <th class="px-4 py-2 border">Mã đơn hàng</th>
                <th class="px-4 py-2 border">Sản phẩm</th>
                <th class="px-4 py-2 border">Tổng tiền</th>
                <th class="px-4 py-2 border">Trạng thái</th>
                <th class="px-4 py-2 border">Chi tiết</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">2025-10-28 17:14:52</td>
                <td class="px-4 py-2">8698088</td>
                <td class="px-4 py-2">ChatGPT Plus 20$ 1 tháng</td>
                <td class="px-4 py-2">419.000đ</td>
                <td class="px-4 py-2 text-orange-500 font-medium">Đang xử lý</td>
                <td class="px-4 py-2 text-blue-600 cursor-pointer">Chi tiết</td>
              </tr>
              <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">2025-10-28 14:27:32</td>
                <td class="px-4 py-2">8697609</td>
                <td class="px-4 py-2">ChatGPT Plus 20$ 14 ngày</td>
                <td class="px-4 py-2">229.000đ</td>
                <td class="px-4 py-2 text-green-600 font-medium">Đã hoàn lại</td>
                <td class="px-4 py-2 text-blue-600 cursor-pointer">Chi tiết</td>
              </tr>
              <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">2025-01-06 14:41:18</td>
                <td class="px-4 py-2">7731394</td>
                <td class="px-4 py-2">Steam Wallet Code 40 HKD</td>
                <td class="px-4 py-2">136.000đ</td>
                <td class="px-4 py-2 text-green-600 font-medium">Đã xử lý</td>
                <td class="px-4 py-2 text-blue-600 cursor-pointer">Chi tiết</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  
  </body>
  @endsection