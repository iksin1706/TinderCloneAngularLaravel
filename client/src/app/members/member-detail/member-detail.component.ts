import { Component, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { NgxGalleryAnimation, NgxGalleryImage, NgxGalleryOptions } from '@kolkov/ngx-gallery';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { TabDirective, TabsetComponent } from 'ngx-bootstrap/tabs';
import { ToastrService } from 'ngx-toastr';
import { take } from 'rxjs';
import { ReportModalComponent } from 'src/app/modals/report-modal/report-modal.component';
import { Member } from 'src/app/_models/member';
import { Message } from 'src/app/_models/message';
import { User } from 'src/app/_models/user';
import { AccountService } from 'src/app/_services/account.service';
import { MembersService } from 'src/app/_services/members.service';
import { MessageService } from 'src/app/_services/message.service';
import { PresenceService } from 'src/app/_services/presence.service';

@Component({
  selector: 'app-member-detail',
  templateUrl: './member-detail.component.html',
  styleUrls: ['./member-detail.component.scss']
})
export class MemberDetailComponent implements OnInit, OnDestroy {

  @ViewChild('memberTabs' ,{static: true}) memberTabs?: TabsetComponent;
  member: Member = {} as Member;
  galleryOptions: NgxGalleryOptions[] = [];
  galleryImages: NgxGalleryImage[] = [];
  activeTab?: TabDirective;
  messages: Message[] = [];
  user?: User;
  bsModalRefBans: BsModalRef<ReportModalComponent> = new BsModalRef<ReportModalComponent>();

  constructor(private accountService: AccountService,
    private route:ActivatedRoute,
    private messageService: MessageService,
    private memberService: MembersService,
    public presenceService: PresenceService,
    private modalService: BsModalService, 
    private toaster: ToastrService){
      this.accountService.currentUser$.pipe(take(1)).subscribe({
        next: user => {
          if(user) this.user=user;
        }
      })
  }
  ngOnDestroy(): void {
    // this.messageService.stopHubConnection();
  }
  ngOnInit(): void {
    this.route.data.subscribe({
      next: data => {
        this.member = data['member'];
        this.memberService.getMember(this.member.username).subscribe({
          next: response => {
            if (response) this.member=response;
            console.log(this.member);
          }
        });
      }
    });
    this.loadMessages();
    this.route.queryParams.subscribe({
      next: params => {
        this.loadMessages();
        params['tab'] && this.selectTab(params['tab']);
      }
    })

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
    if(!this.member) return [];
    const imageUrls= [];
    console.log(this.member.photos);
    console.log(this.member.photos);
    console.log(this.member.photos);
    for(const photo of this.member.photos){
        imageUrls.push({
          small: photo.url,
          medium: photo.url,
          big: photo.url
        })
    }
    return imageUrls;
  }
  selectTab(heading: string){
    if(this.memberTabs){
      this.memberTabs.tabs.find(x => x.heading === heading)!.active = true;
    }
  }

  loadMessages(){
    if(this.member){
      this.messageService.getMessageThread(this.member.username).subscribe({
        next: messages => this.messages = messages
      })
    }
  }

  onTabActivated(data: TabDirective) {
    this.activeTab = data;
    if(this.activeTab.heading==='Messages' && this.user){

    } else {
    }
  }
  openReportModal(username:string) {
    const config = {
      class: 'modal-dialog-centered',
      initialState: {
        username: username,
      }
    }
    this.bsModalRefBans = this.modalService.show(ReportModalComponent, config);
  }

}
