import 'dart:convert';
import 'package:http/http.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';

import 'place_model.dart';

class PlaceApiProvider {
  final client = Client();

  PlaceApiProvider(this.sessionToken);

  final String? sessionToken;

  final String apiKey = Constants.googleMapKey;

  Future<List<Suggestion>> fetchSuggestions(String input, String lang) async {
    const country = 'ind';
    final request =
        'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$input&language=$lang&components=country:$country&key=$apiKey&sessiontoken=$sessionToken';
    final response = await client.get(Uri.parse(request));

    if (response.statusCode == 200) {
      final result = json.decode(response.body);
      if (result['status'] == 'OK') {
        List<Suggestion> suggestions = result['predictions']
            .map<Suggestion>((p) => Suggestion(
                  placeId: p['place_id'],
                  description: p['description'],
                  mainText: p['structured_formatting']['main_text'],
                  secondaryText: p['structured_formatting']['secondary_text'],
                ))
            .toList();
        return suggestions;
      }
      if (result['status'] == 'ZERO_RESULTS') {
        return [];
      }
      throw Exception(result['error_message']);
    } else {
      throw Exception('Failed to fetch suggestion');
    }
  }

  Future<PlaceJay?> getPlaceDetailFromId(String? placeId) async {
    final request =
        'https://maps.googleapis.com/maps/api/geocode/json?place_id=$placeId&key=$apiKey&sessiontoken=$sessionToken';
    final response = await client.get(Uri.parse(request));

    if (response.statusCode == 200) {
      final result = json.decode(response.body);
      if (result['status'] == 'OK') {
        final aaa = result['results'][0];

        List<dynamic> addressComponents = aaa['address_components'];

        String? lineTwo;
        String? city;
        String? state;
        String? zipCode;

        lineTwo = aaa['formatted_address'].toString().split(',').first;
        for (var component in addressComponents) {
          List<dynamic> types = component['types'];

          if (types.contains("postal_code")) {
            zipCode = component['long_name'];
          }

          if (types.contains("administrative_area_level_1")) {
            state = component['long_name'];
          }

          if (types.contains("locality")) {
            city = component['long_name'];
          }
        }

        final place = PlaceJay(
          lat: aaa['geometry']['location']['lat'],
          lng: aaa['geometry']['location']['lng'],
          lineTwo: lineTwo,
          city: city,
          state: state,
          zipCode: zipCode,
        );
        return place;
      }
      throw Exception(result['error_message']);
    } else {
      throw Exception('Failed to fetch suggestion');
    }
  }
}
