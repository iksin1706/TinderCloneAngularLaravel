import { Component, Input, OnInit } from '@angular/core';
import { NgxGalleryAnimation, NgxGalleryImage, NgxGalleryOptions } from '@kolkov/ngx-gallery';
import { Member } from 'src/app/_models/member';

@Component({
  selector: 'app-member-profile-card',
  templateUrl: './member-profile-card.component.html',
  styleUrls: ['./member-profile-card.component.scss']
})
export class MemberProfileCardComponent implements OnInit{
  @Input() member: Member | undefined
  @Input() profileLink: boolean = false;
  galleryOptions: NgxGalleryOptions[] = [];
  galleryImages: NgxGalleryImage[] = []

  ngOnInit(){
    this.galleryOptions = [
      {
        width: '500px',
        height: '50rem',
        imagePercent: 100,
        thumbnails: false,
        imageAnimation: NgxGalleryAnimation.Slide,
        preview: false,
      }
    ]
    this.galleryImages = this.getImages();
  }
  getImages() {
    if (!this.member) return [];
    const imageUrls = [];
    console.log(this.member.photos);
    console.log(this.member.photos);
    console.log(this.member.photos);
    this.member.photos.sort(value => {
      return value.isMain ? -1 : 1 
    })
   console.log("TEST");
    for (const photo of this.member.photos) {
      imageUrls.push({
        small: photo.url,
        medium: photo.url,
        big: photo.url
      })
    }
    
    return imageUrls;
  }
  
}
