import { Photo } from "./photo";

export interface Member {
    userName:     string;
    gender:       string;
    age:          number;
    knownAs:      string;
    photoUrl:     string;
    created:      Date;
    lastActive:   Date;
    introduction: string;
    lookingFor:   string;
    interests:    string;
    city:         string;
    country:      string;
    photos:       Photo[];
}

  