import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/modules/add_child/bindings/add_child_binding.dart';
import 'package:jazz_smart_pay/app/modules/add_child/views/add_child_view.dart';
import 'package:jazz_smart_pay/app/modules/change_password/views/change_password_view.dart';
import 'package:jazz_smart_pay/app/modules/coupon_code/views/coupon_code_view.dart';
import 'package:jazz_smart_pay/app/modules/credit_transfer/bindings/credit_transfer_binding.dart';
import 'package:jazz_smart_pay/app/modules/family_list/bindings/family_list_binding.dart';
import 'package:jazz_smart_pay/app/modules/feedback/bindings/feedback_binding.dart';
import 'package:jazz_smart_pay/app/modules/feedback/views/feedback_view.dart';
import 'package:jazz_smart_pay/app/modules/my_profile/bindings/my_profile_binding.dart';
import 'package:jazz_smart_pay/app/modules/my_profile/views/my_profile_view.dart';
import 'package:jazz_smart_pay/app/modules/notification/bindings/notification_binding.dart';
import 'package:jazz_smart_pay/app/modules/notification/views/notification_view.dart';
import 'package:jazz_smart_pay/app/modules/orders_details/bindings/orders_details_binding.dart';
import 'package:jazz_smart_pay/app/modules/orders_details/views/orders_details_view.dart';
import 'package:jazz_smart_pay/app/modules/pay_for_service/bindings/pay_for_service_binding.dart';
import 'package:jazz_smart_pay/app/modules/pay_for_service/views/pay_for_service_view.dart';
import 'package:jazz_smart_pay/app/modules/pdf_view/views/pdf_view.dart';
import 'package:jazz_smart_pay/app/modules/pre_order/bindings/pre_order_binding.dart';
import 'package:jazz_smart_pay/app/modules/pre_order/views/pre_order_view.dart';
import 'package:jazz_smart_pay/app/modules/register/bindings/register_binding.dart';
import 'package:jazz_smart_pay/app/modules/register/views/register_view.dart';
import 'package:jazz_smart_pay/app/modules/seller_register/bindings/seller_register_binding.dart';
import 'package:jazz_smart_pay/app/modules/seller_register/views/seller_register_view.dart';
import 'package:jazz_smart_pay/app/modules/sub_cat/bindings/sub_cat_binding.dart';
import 'package:jazz_smart_pay/app/modules/sub_sub_cat/bindings/sub_sub_cat_binding.dart';
import 'package:jazz_smart_pay/app/modules/sub_sub_cat/views/sub_sub_cat_view.dart';
import 'package:jazz_smart_pay/app/modules/top_up/bindings/top_up_binding.dart';
import 'package:jazz_smart_pay/app/modules/top_up/views/top_up_view.dart';
import '../modules/add_address/bindings/add_address_binding.dart';
import '../modules/add_address/views/add_address_view.dart';
import '../modules/address/bindings/address_binding.dart';
import '../modules/address/views/address_view.dart';
import '../modules/all_cat/bindings/all_cat_binding.dart';
import '../modules/all_cat/views/all_cat_view.dart';
import '../modules/base_page/bindings/base_page_binding.dart';
import '../modules/base_page/views/base_page_view.dart';
import '../modules/cart/bindings/cart_binding.dart';
import '../modules/cart/views/cart_view.dart';
import '../modules/change_password/bindings/change_password_binding.dart';
import '../modules/checkout/bindings/checkout_binding.dart';
import '../modules/checkout/views/checkout_view.dart';
import '../modules/coupon_code/bindings/coupon_code_binding.dart';
import '../modules/chat/bindings/chat_binding.dart';
import '../modules/chat/views/chat_view.dart';
import '../modules/credit_transfer/views/credit_transfer_view.dart';
import '../modules/family_list/views/family_list_view.dart';
import '../modules/forgot_password/bindings/forgot_password_binding.dart';
import '../modules/forgot_password/views/forgot_password_view.dart';
import '../modules/history/bindings/history_binding.dart';
import '../modules/history/views/history_view.dart';
import '../modules/home/bindings/home_binding.dart';
import '../modules/home/views/home_view.dart';
import '../modules/home_search/bindings/home_search_binding.dart';
import '../modules/home_search/views/home_search_view.dart';
import '../modules/login/bindings/login_binding.dart';
import '../modules/login/views/login_view.dart';
import '../modules/onboard/bindings/onboard_binding.dart';
import '../modules/onboard/views/onboard_view.dart';
import '../modules/orders/bindings/orders_binding.dart';
import '../modules/orders/views/orders_view.dart';
import '../modules/pdf_view/bindings/pdf_view_binding.dart';
import '../modules/product/bindings/product_binding.dart';
import '../modules/product/views/product_view.dart';
import '../modules/product_list/bindings/product_list_binding.dart';
import '../modules/product_list/views/product_list_view.dart';
import '../modules/profile/bindings/profile_binding.dart';
import '../modules/profile/views/profile_view.dart';
import '../modules/search/bindings/search_binding.dart';
import '../modules/search/views/search_view.dart';
import '../modules/splash/bindings/splash_binding.dart';
import '../modules/splash/views/splash_view.dart';
import '../modules/sub_cat/views/sub_cat_view.dart';
import '../modules/wishlist/bindings/wishlist_binding.dart';
import '../modules/wishlist/views/wishlist_view.dart';

part 'app_routes.dart';

class AppPages {
  AppPages._();

  static const INITIAL = Routes.SPLASH;

  static final routes = [
    GetPage(
      name: _Paths.HOME,
      page: () => const HomeView(),
      binding: HomeBinding(),
    ),
    GetPage(
      name: _Paths.LOGIN,
      page: () => const LoginView(),
      binding: LoginBinding(),
    ),
    GetPage(
      name: _Paths.REGISTER,
      page: () => const RegisterView(),
      binding: RegisterBinding(),
    ),
    GetPage(
      name: _Paths.SELLER_REGISTER,
      page: () => const SellerRegisterView(),
      binding: SellerRegisterBinding(),
    ),
    GetPage(
      name: _Paths.CHAT,
      page: () => const ChatView(),
      binding: ChatBinding(),
    ),
    GetPage(
      name: _Paths.PROFILE,
      page: () => const ProfileView(),
      binding: ProfileBinding(),
    ),
    GetPage(
      name: _Paths.ONBOARD,
      page: () => const OnboardView(),
      binding: OnboardBinding(),
    ),
    GetPage(
      name: _Paths.CART,
      page: () => const CartView(showBack: true),
      binding: CartBinding(),
    ),
    GetPage(
      name: _Paths.HOME_SEARCH,
      page: () => const HomeSearchView(),
      binding: HomeSearchBinding(),
    ),
    GetPage(
      name: _Paths.SEARCH,
      page: () => const SearchView(),
      binding: SearchBinding(),
    ),
    GetPage(
      name: _Paths.FORGOT_PASSWORD,
      page: () => const ForgotPasswordView(),
      binding: ForgotPasswordBinding(),
    ),
    GetPage(
      name: _Paths.CHANGE_PASSWORD,
      page: () => const ChangePasswordView(),
      binding: ChangePasswordBinding(),
    ),
    GetPage(
      name: _Paths.WISHLIST,
      page: () => const WishlistView(),
      binding: WishlistBinding(),
    ),
    GetPage(
      name: _Paths.ORDERS,
      page: () => const OrdersView(),
      binding: OrdersBinding(),
    ),
    GetPage(
      name: _Paths.PRODUCT,
      page: () => const ProductView(),
      binding: ProductBinding(),
    ),
    GetPage(
      name: _Paths.PRODUCT_LIST,
      page: () => const ProductListView(),
      binding: ProductListBinding(),
    ),
    GetPage(
      name: _Paths.CHECKOUT,
      page: () => const CheckoutView(),
      binding: CheckoutBinding(),
    ),
    GetPage(
      name: _Paths.ADDRESS,
      page: () => const AddressView(),
      binding: AddressBinding(),
    ),
    GetPage(
      name: _Paths.ADD_ADDRESS,
      page: () => const AddAddressView(),
      binding: AddAddressBinding(),
    ),
    GetPage(
      name: _Paths.SPLASH,
      page: () => const SplashView(),
      binding: SplashBinding(),
    ),
    GetPage(
      name: _Paths.BASE_PAGE,
      page: () => const BasePageView(),
      binding: BasePageBinding(),
      // middlewares: [AuthMiddleware()],
    ),
    GetPage(
      name: _Paths.SUB_CAT,
      page: () => const SubCatView(),
      binding: SubCatBinding(),
    ),
    GetPage(
      name: _Paths.SUB_SUB_CAT,
      page: () => const SubSubCatView(),
      binding: SubSubCatBinding(),
    ),
    GetPage(
      name: _Paths.ALL_CAT,
      page: () => const AllCatView(),
      binding: AllCatBinding(),
    ),
    GetPage(
      name: _Paths.NOTIFICATION,
      page: () => const NotificationView(),
      binding: NotificationBinding(),
    ),
    GetPage(
      name: _Paths.COUPON_CODE,
      page: () => const CouponCodeView(),
      binding: CouponCodeBinding(),
    ),
    GetPage(
      name: _Paths.ORDER_DETAILS,
      page: () => const OrderDetailsView(),
      binding: OrderDetailsBinding(),
    ),
    GetPage(
      name: _Paths.FEEDBACK,
      page: () => const FeedbackView(),
      binding: FeedbackBinding(),
    ),
    GetPage(
      name: _Paths.MY_PROFILE,
      page: () => const MyProfileView(),
      binding: MyProfileBinding(),
    ),
    GetPage(
      name: _Paths.FAMILY,
      page: () => const FamilyListView(),
      binding: FamilyListBinding(),
    ),
    GetPage(
      name: _Paths.ADD_CHILD,
      page: () => const AddChildView(),
      binding: AddChildBinding(),
    ),
    GetPage(
      name: _Paths.TOP_UP,
      page: () => const TopUpView(),
      binding: TopUpBinding(),
    ),
    GetPage(
      name: _Paths.CREDIT_TRANSFER,
      page: () => const CreditTransferView(),
      binding: CreditTransferBinding(),
    ),
    GetPage(
      name: _Paths.HISTORY,
      page: () => const HistoryView(),
      binding: HistoryBinding(),
    ),
    GetPage(
      name: _Paths.PRE_ORDER,
      page: () => const PreOrderView(),
      binding: PreOrderBinding(),
    ),
    GetPage(
      name: _Paths.PAY_FOR_SERVICE,
      page: () => const PayForServiceView(),
      binding: PayForServiceBinding(),
    ),
    GetPage(
      name: _Paths.PDF_VIEW,
      page: () => const PdfView(),
      binding: PdfViewBinding()
    ),
  ];
}
