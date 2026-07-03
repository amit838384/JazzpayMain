import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/helpers/theme_setup.dart';
import 'app/routes/app_pages.dart';
import 'app/utils/constant_vars.dart';

class JazzSmartPayApp extends StatefulWidget {
  final FlutterLocalization _localization = FlutterLocalization.instance;

  JazzSmartPayApp({super.key});

  @override
  State<JazzSmartPayApp> createState() => _JazzSmartPayAppState();
}

class _JazzSmartPayAppState extends State<JazzSmartPayApp> {
  bool _localizationReady = false;

  @override
  void initState() {
    super.initState();
    loadLocalization();
  }

  Future<void> loadLocalization() async {
    final enJson = await rootBundle.loadString('assets/lang/en.json');
    final arJson = await rootBundle.loadString('assets/lang/ar.json');

    widget._localization.init(
      mapLocales: [
        MapLocale('en', json.decode(enJson)),
        MapLocale('ar', json.decode(arJson)),
      ],
      initLanguageCode: 'en',
    );

    Constants.localization = widget._localization;

    setState(() {
      _localizationReady = true;
    });
  }

  @override
  Widget build(BuildContext context) {
    if (!_localizationReady) {
      return const MaterialApp(
        debugShowCheckedModeBanner: false,
        home: Scaffold(
          body: Center(child: CircularProgressIndicator()),
        ),
      );
    }

    Constants.screenSize = MediaQuery.of(context).size;

    return RestartWidget(
      onRestart: (_) => setState(() {}),
      child: GestureDetector(
        onTap: () => KeyboardUtil.hideKeyboard(context),
        child: GetMaterialApp(
          debugShowCheckedModeBanner: false,
          title: 'Jazz Pay',

          // Localization
          locale: widget._localization.currentLocale,
          supportedLocales: widget._localization.supportedLocales,
          localizationsDelegates: [
            ...widget._localization.localizationsDelegates,
          ],
          fallbackLocale: const Locale('en', 'US'),

          // Theme & Routing
          themeMode: ThemeMode.light,
          darkTheme: Themes.dark,
          initialRoute: AppPages.INITIAL,
          getPages: AppPages.routes,

          builder: (context, child) {
            return MediaQuery(
              data: MediaQuery.of(context).copyWith(
                textScaler: const TextScaler.linear(0.98),
              ),
              child: child!,
            );
          },
        ),
      ),
    );
  }
}

// class JazzSmartPayApp extends StatefulWidget {
//   final FlutterLocalization _localization = FlutterLocalization.instance;
//   JazzSmartPayApp({super.key});
// // Ashraf
//   @override
//   State<JazzSmartPayApp> createState() => _JazzSmartPayAppState();
// }

// class _JazzSmartPayAppState extends State<JazzSmartPayApp> {
//   @override
//   void initState() {
//     super.initState();
//     loadLocalization();
//   }

//   void loadLocalization() async {
//     final enJson = await rootBundle.loadString('assets/lang/en.json');
//     final arJson = await rootBundle.loadString('assets/lang/ar.json');

//     widget._localization.init(
//       mapLocales: [
//         MapLocale('en', json.decode(enJson)),
//         MapLocale('ar', json.decode(arJson)),
//       ],
//       initLanguageCode: 'en',
//     );

//     setState(() {}); // Force rebuild with localization loaded
//     Constants.localization = widget._localization;
//   }

//   bool isRestarted = false;
//   @override
//   Widget build(BuildContext context) {
//     Constants.screenSize = MediaQuery.of(context).size;
//     return RestartWidget(
//       onRestart: (value) {
//         setState(() {
//           isRestarted = true;
//         });
//       },
//       child: GestureDetector(
//         onTap: () => KeyboardUtil.hideKeyboard(context),
//         child: GetMaterialApp(
//           debugShowCheckedModeBanner: false,
//           builder: (context, child) {
//             return MediaQuery(
//               data: MediaQuery.of(context).copyWith(
//                 textScaler: const TextScaler.linear(0.98),
//               ),
//               child: child!,
//             );
//           },
//           // translations: AppTranslations(),
//           // supportedLocales: widget._localization.supportedLocales,
//           locale: Locale('en'),
//           supportedLocales: [
//             Locale('en'), // English
//             Locale('ar'), // Arabic
//             // Add more as needed
//           ],
//           localizationsDelegates: widget._localization.localizationsDelegates,
//           // locale: widget._localization.currentLocale,
//           fallbackLocale: const Locale(
//               'en', 'US'), // Fallback language if translation is missing
//           title: "Jazz Pay",
//           themeMode: ThemeMode.light,
//           darkTheme: Themes.dark,
//           initialRoute: AppPages.INITIAL,
//           getPages: AppPages.routes,
//         ),
//       ),
//     );
//   }
// }

class RestartWidget extends StatefulWidget {
  const RestartWidget({super.key, required this.child, this.onRestart});

  final Widget child;
  final void Function(bool)? onRestart;
  static bool isRestarted = false;

  static void restartApp(BuildContext context) {
    isRestarted = true;
    context.findAncestorStateOfType<_RestartWidgetState>()?.restartApp();
  }

  @override
  State<RestartWidget> createState() => _RestartWidgetState();
}

class _RestartWidgetState extends State<RestartWidget> {
  Key key = UniqueKey();

  void restartApp() {
    setState(() {
      key = UniqueKey();
    });

    if (widget.onRestart != null) {
      widget.onRestart!(true);
    }
  }

  @override
  Widget build(BuildContext context) {
    return KeyedSubtree(
      key: key,
      child: widget.child,
    );
  }
}

class KeyboardUtil {
  static void hideKeyboard(BuildContext context) {
    try {
      SystemChannels.textInput.invokeMethod("TextInput.hide");
      final FocusScopeNode currentFocus = FocusScope.of(context);
      if (currentFocus.hasPrimaryFocus) {
        currentFocus.unfocus();
      }
    } catch (e) {
      debugPrint("HideKeyboardError: $e");
    }
  }
}
