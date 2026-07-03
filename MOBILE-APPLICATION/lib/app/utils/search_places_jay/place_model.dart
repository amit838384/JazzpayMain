
class Suggestion {
  final String? placeId;
  final String? description;
  final String? mainText;
  final String? secondaryText;

  Suggestion({
    this.placeId,
    this.description,
    this.mainText,
    this.secondaryText,
  });

  @override
  String toString() {
    return 'Suggestion(description: $description, placeId: $placeId)';
  }
}

class PlaceJay {
  final String? lineOne;
  final String? lineTwo;
  final double? lat;
  final double? lng;
  final String? city;
  final String? state;
  final String? zipCode;

  PlaceJay({
    this.city,
    this.state,
    this.zipCode,
    this.lineOne,
    this.lineTwo,
    this.lat,
    this.lng,
  });

  @override
  String toString() {
    return 'Place(LineOne: $lineOne, LineTwo: $lineTwo, latitude: $lat, longitude: $lng, city: $city, state: $state, zipcode: $zipCode)';
  }

  PlaceJay copyWith({
    String? lineOne,
    String? lineTwo,
    double? lat,
    double? lng,
    String? city,
    String? state,
    String? zipCode,
  }) =>
      PlaceJay(
        lineOne: lineOne ?? this.lineOne,
        lineTwo: lineTwo ?? this.lineTwo,
        lat: lat ?? this.lat,
        lng: lng ?? this.lng,
        city: city ?? this.city,
        state: state ?? this.state,
        zipCode: zipCode ?? this.zipCode,
      );
}
