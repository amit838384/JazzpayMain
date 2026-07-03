import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';

import '../../../exports.dart';
import '../../custom_widget/app_text_field.dart';

Future<T?> showPlacesSearch<T>({
  required BuildContext context,
  required SearchPlaceDelegate<T> delegate,
  String? query = '',
  bool useRootNavigator = false,
}) {
  delegate.query = query ?? delegate.query;
  delegate._currentBody = _SearchBody.suggestions;
  return Navigator.of(context, rootNavigator: useRootNavigator)
      .push(_SearchPageRoute<T>(delegate: delegate));
}

String? googleMapKey = Constants.googleMapKey;

abstract class SearchPlaceDelegate<T> {
  SearchPlaceDelegate(
      {this.searchFieldLabel, this.searchFieldStyle, this.keyboardType})
      : assert(searchFieldStyle == null);

  Widget buildSuggestions(BuildContext context);

  String get query => _queryTextController.text;

  set query(String value) {
    _queryTextController.text = value;
    if (_queryTextController.text.isNotEmpty) {
      _queryTextController.selection = TextSelection.fromPosition(
        TextPosition(offset: _queryTextController.text.length),
      );
    }
  }

  final String title = "Set Delivery Location";

  set title(String value) {
    title = value;
  }

  void showSuggestions(BuildContext context) {
    assert(_focusNode != null,
        '_focusNode must be set by route before showSuggestions is called.');
    _focusNode!.requestFocus();
    _currentBody = _SearchBody.suggestions;
  }

  void close(BuildContext context, T result) {
    _currentBody = null;
    _focusNode?.unfocus();
    Navigator.of(context)
      ..popUntil((Route<dynamic> route) => route == _route)
      ..pop(result);
  }

  final String? searchFieldLabel;

  final TextStyle? searchFieldStyle;

  final TextInputType? keyboardType;

  Animation<double> get transitionAnimation => _proxyAnimation;

  FocusNode? _focusNode;

  final TextEditingController _queryTextController = TextEditingController();

  final ProxyAnimation _proxyAnimation =
      ProxyAnimation(kAlwaysDismissedAnimation);

  final ValueNotifier<_SearchBody?> _currentBodyNotifier =
      ValueNotifier<_SearchBody?>(null);

  _SearchBody? get _currentBody => _currentBodyNotifier.value;
  set _currentBody(_SearchBody? value) {
    _currentBodyNotifier.value = value;
  }

  _SearchPageRoute<T>? _route;
}

enum _SearchBody { suggestions }

class _SearchPageRoute<T> extends PageRoute<T> {
  _SearchPageRoute({required this.delegate}) {
    assert(
      delegate._route == null,
      'The ${delegate.runtimeType} instance is currently used by another active '
      'search. Please close that search by calling close() on the SearchPlaceDelegate '
      'before opening another search with the same delegate instance.',
    );
    delegate._route = this;
  }

  final SearchPlaceDelegate<T> delegate;

  @override
  Color? get barrierColor => null;

  @override
  String? get barrierLabel => null;

  @override
  Duration get transitionDuration => const Duration(milliseconds: 300);

  @override
  bool get maintainState => false;

  @override
  Widget buildTransitions(
    BuildContext context,
    Animation<double> animation,
    Animation<double> secondaryAnimation,
    Widget child,
  ) {
    return FadeTransition(opacity: animation, child: child);
  }

  @override
  Animation<double> createAnimation() {
    final Animation<double> animation = super.createAnimation();
    delegate._proxyAnimation.parent = animation;
    return animation;
  }

  @override
  Widget buildPage(BuildContext context, Animation<double> animation,
      Animation<double> secondaryAnimation) {
    return _SearchPage<T>(delegate: delegate, animation: animation);
  }

  @override
  void didComplete(T? result) {
    super.didComplete(result);
    assert(delegate._route == this);
    delegate._route = null;
    delegate._currentBody = null;
  }
}

class _SearchPage<T> extends StatefulWidget {
  const _SearchPage({required this.delegate, required this.animation});

  final SearchPlaceDelegate<T> delegate;
  final Animation<double> animation;

  @override
  State<StatefulWidget> createState() => _SearchPageState<T>();
}

class _SearchPageState<T> extends State<_SearchPage<T>> {
  // This node is owned, but not hosted by, the search page. Hosting is done by
  // the text field.
  FocusNode focusNode = FocusNode();

  @override
  void initState() {
    super.initState();
    widget.delegate._queryTextController.addListener(_onQueryChanged);
    widget.animation.addStatusListener(_onAnimationStatusChanged);
    widget.delegate._currentBodyNotifier.addListener(_onSearchBodyChanged);
    focusNode.addListener(_onFocusChanged);
    widget.delegate._focusNode = focusNode;
  }

  @override
  void dispose() {
    super.dispose();
    widget.delegate._queryTextController.removeListener(_onQueryChanged);
    widget.animation.removeStatusListener(_onAnimationStatusChanged);
    widget.delegate._currentBodyNotifier.removeListener(_onSearchBodyChanged);
    widget.delegate._focusNode = null;
    focusNode.dispose();
  }

  void _onAnimationStatusChanged(AnimationStatus status) {
    if (status != AnimationStatus.completed) {
      return;
    }
    widget.animation.removeStatusListener(_onAnimationStatusChanged);
    if (widget.delegate._currentBody == _SearchBody.suggestions) {
      focusNode.requestFocus();
    }
  }

  @override
  void didUpdateWidget(_SearchPage<T> oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (widget.delegate != oldWidget.delegate) {
      oldWidget.delegate._queryTextController.removeListener(_onQueryChanged);
      widget.delegate._queryTextController.addListener(_onQueryChanged);
      oldWidget.delegate._currentBodyNotifier
          .removeListener(_onSearchBodyChanged);
      widget.delegate._currentBodyNotifier.addListener(_onSearchBodyChanged);
      oldWidget.delegate._focusNode = null;
      widget.delegate._focusNode = focusNode;
    }
  }

  void _onFocusChanged() {
    if (focusNode.hasFocus &&
        widget.delegate._currentBody != _SearchBody.suggestions) {
      widget.delegate.showSuggestions(context);
    }
  }

  void _onQueryChanged() {
    setState(() {
      // rebuild ourselves because query changed.
    });
  }

  void _onSearchBodyChanged() {
    setState(() {
      // rebuild ourselves because search body changed.
    });
  }

  @override
  Widget build(BuildContext context) {
    assert(debugCheckHasMaterialLocalizations(context));
    final String searchFieldLabel = widget.delegate.searchFieldLabel ??
        MaterialLocalizations.of(context).searchFieldLabel;
    Widget? body;
    switch (widget.delegate._currentBody) {
      case _SearchBody.suggestions:
        body = KeyedSubtree(
          key: const ValueKey<_SearchBody>(_SearchBody.suggestions),
          child: widget.delegate.buildSuggestions(context),
        );
        break;
      case null:
        break;
    }

    return Semantics(
      explicitChildNodes: true,
      scopesRoute: true,
      namesRoute: true,
      child: Scaffold(
        backgroundColor: bgColor,
        body: SafeArea(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            children: [
              Row(
                children: [
                  IconButton(
                    splashRadius: 24,
                    icon: CircleAvatar(
                      backgroundColor: fillColor,
                      child: Icon(Icons.clear_rounded, color: blackColor),
                    ),
                    onPressed: () => Navigator.of(context).pop(),
                    // color: Kolors.appBlack,
                  ),
                  Expanded(
                    child: AppTextField(
                      controller: widget.delegate._queryTextController,
                      focusNode: focusNode,
                      hintText: searchFieldLabel,
                      prefixWidget: const Icon(Icons.location_on_outlined,
                          color: Colors.black26),
                      textInputAction: TextInputAction.search,
                      keyboardType: widget.delegate.keyboardType,
                      autofocus: true,
                    ),
                  ),
                ],
              ),
              Expanded(
                  child: AnimatedSwitcher(
                      duration: const Duration(milliseconds: 300),
                      child: body)),
            ],
          ),
        ),
      ),
    );
  }
}
