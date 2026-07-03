import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';

class Themes {
  static final dark = ThemeData.dark().copyWith(
    textTheme: ThemeData.dark().textTheme.apply(fontFamily: 'Afacad'),
    scaffoldBackgroundColor: Colors.black,
    bottomNavigationBarTheme: const BottomNavigationBarThemeData().copyWith(
      backgroundColor: fillColor,
      selectedItemColor: greenColor,
      unselectedItemColor: hintColor,
      type: BottomNavigationBarType.fixed,
      showSelectedLabels: false,
      showUnselectedLabels: false,
    ),
    popupMenuTheme: const PopupMenuThemeData(
      color: Color(0xFF1C1C1C),
      surfaceTintColor: Colors.transparent,
    ),
    colorScheme: const ColorScheme.dark(
      error: Color(0xFFB00020),
    ),
    dialogBackgroundColor: const Color(0xFF262626),
    dialogTheme: const DialogThemeData(
      surfaceTintColor: Colors.black,
    ),
    highlightColor: Colors.transparent,
    splashColor: Colors.transparent,
    appBarTheme: const AppBarTheme().copyWith(
      surfaceTintColor: Colors.transparent,
      systemOverlayStyle: SystemUiOverlayStyle.light,
      backgroundColor: bgColor,
    ),
    iconButtonTheme: IconButtonThemeData(
      style: IconButton.styleFrom(
        highlightColor: Colors.transparent,
      ),
    ),
    tabBarTheme: TabBarThemeData(
      splashFactory: NoSplash.splashFactory,
      overlayColor:
          WidgetStateProperty.resolveWith<Color?>((Set<WidgetState> states) {
        return states.contains(WidgetState.focused) ? null : Colors.transparent;
      }),
    ),
    outlinedButtonTheme: OutlinedButtonThemeData(
      style: OutlinedButton.styleFrom(
        side: const BorderSide(
          width: 1.5,
          color: Colors.white,
        ),
        foregroundColor: Colors.white,
      ),
    ),
    textButtonTheme: TextButtonThemeData(
      style: TextButton.styleFrom(
        foregroundColor: greenColor,
      ),
    ),
  );
}
