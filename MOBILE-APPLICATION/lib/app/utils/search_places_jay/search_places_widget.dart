import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';

import '../../custom_widget/app_divider.dart';
import '../../custom_widget/app_text.dart';
import '../../custom_widget/bouncing_button.dart';
import 'place_api_provider.dart';
import 'place_model.dart';
import 'search_places.dart';

class AddressSearch extends SearchPlaceDelegate<PlaceJay?> {
  AddressSearch(this.sessionToken, {this.topTitle}) {
    apiClient = PlaceApiProvider(sessionToken);
  }

  final String? sessionToken;
  final String? topTitle;

  late PlaceApiProvider apiClient;

  @override
  String get title {
    if (topTitle != null) {
      return topTitle!;
    } else {
      return 'Search location';
    }
  }

  @override
  Widget buildSuggestions(BuildContext context) {
    return suggestionsResults(context);
  }

  FutureBuilder<List<Suggestion>> suggestionsResults(BuildContext context) {
    return FutureBuilder(
      future: query == "" || query.length < 3
          ? null
          : apiClient.fetchSuggestions(
              query,
              Localizations.localeOf(context).languageCode,
            ),
      builder: (context, snapshot) {
        // if (snapshot.hasData) {
        //   List<Suggestion>? suggestion = snapshot.data;
        // }
        return query == ''
            ? const SizedBox()
            : query.length < 3
                ? Align(
                    alignment: Alignment.topCenter,
                    child: AppText.smallParagraph(
                        'Please enter min 3 letters to search.'),
                  )
                : snapshot.hasData
                    ? Column(
                        children: [
                          if (snapshot.connectionState ==
                              ConnectionState.waiting)
                            const LinearProgressIndicator(minHeight: 2),
                          Expanded(
                            child: Container(
                              margin: const EdgeInsets.all(8),
                              decoration: BoxDecoration(
                                  borderRadius: BorderRadius.circular(16),
                                  color: bgColor),
                              child: ListView.separated(
                                separatorBuilder: (context, index) =>
                                    appDivider(),
                                itemBuilder: (context, index) => Bouncing(
                                  onTap: () async {
                                    final selectedPlace = snapshot.data![index];
                                    await apiClient
                                        .getPlaceDetailFromId(
                                            selectedPlace.placeId)
                                        .then((value) {
                                      final aaa = value?.copyWith(
                                          lineOne: selectedPlace.mainText);
                                      close(context, aaa);
                                    });
                                  },
                                  child: Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: Row(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Icon(
                                          CupertinoIcons.placemark,
                                          color: primaryColor,
                                          size: 32,
                                        ),
                                        const SizedBox(width: 4),
                                        Expanded(
                                          child: Column(
                                            crossAxisAlignment:
                                                CrossAxisAlignment.start,
                                            children: [
                                              AppText.heading3(
                                                (snapshot.data![index])
                                                        .mainText ??
                                                    "",
                                                fontWeight: FontWeight.w600,
                                              ),
                                              AppText.smallParagraph(
                                                  (snapshot.data![index])
                                                          .secondaryText ??
                                                      ""),
                                            ],
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ),
                                itemCount: snapshot.data!.length,
                              ),
                            ),
                          ),
                        ],
                      )
                    : const Align(
                        alignment: Alignment.topCenter,
                        child: LinearProgressIndicator(minHeight: 2),
                      );
      },
    );
  }
}
