import { animate, state, style, transition, trigger } from '@angular/animations';
import { Component, Input } from '@angular/core';
import { Member } from '../_models/member';
import { Pagination } from '../_models/pagination';
import { UserParams } from '../_models/userParams';
import { MembersService } from '../_services/members.service';

@Component({
  selector: 'app-swipe-cards',
  templateUrl: './swipe-cards.component.html',
  styleUrls: ['./swipe-cards.component.scss'],
animations: [
    trigger('fadeout', [
      state('start', style({
        opacity: 1,
        transform: 'translateY(0)'
      })),
      transition('start => left', [
        style({ opacity: 1 }),
        animate(400, style({ opacity: 0, transform: 'translateX(-100%)' }))
      ]),
      transition('start => right', [
        style({ opacity: 1 }),
        animate(400, style({ opacity: 0, transform: 'translateX(-100%)' }))
      ])
    ]),
  ]
})
export class SwipeCardsComponent {
   members : Member[] = [] ;
   pagination: Pagination | undefined;
   userParams: UserParams | undefined;
    animationState: string = 'start';

   constructor(private memberService: MembersService) {
    this.userParams = this.memberService.getUserParams();
  }

  ngOnInit(): void {
    this.loadMembers();
  }

  loadMembers() {
    if (this.userParams){
      this.memberService.setUserParams(this.userParams);
      this.memberService.getMembers(this.userParams).subscribe({
        next: response => {         
          if (response.result && response.pagination) {
            this.members = response.result;
            console.log(this.members);
            this.pagination = response.pagination;          
          }
        }
      })
    }
  }

  removeMember(member:Member){
    this.members = this.members.filter(object => {
      return object.userName !== member.userName;
    });
  }

  likeUser(member:Member){
    this.removeMember(member);
  }
  dislikeUser(member:Member){
    this.removeMember(member);
  }

  fadeOutToLeft() {
    this.animationState='left';
  }

  fadeOutToRight() {
    this.animationState='right';
  }
  resetAnimationState(state:any) {
    this.animationState = 'start';
  }
}
