import 'package:intl/intl.dart';

String currency(double? amount) {
  if (amount == null) {
    return '';
  }

  bool hasDecimal = amount % 1 != 0;

  final formatCurrency = NumberFormat.simpleCurrency(
    name: 'INR',
    decimalDigits: hasDecimal ? 2 : 0,
  );

  return formatCurrency.format(amount);
}
