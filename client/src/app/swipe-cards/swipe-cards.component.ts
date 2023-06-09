import { animate, keyframes, state, style, transition, trigger } from '@angular/animations';
import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { Member } from '../_models/member';
import { Pagination } from '../_models/pagination';
import { UserParams } from '../_models/userParams';
import { MembersService } from '../_services/members.service';

@Component({
  selector: 'app-swipe-cards',
  templateUrl: './swipe-cards.component.html',
  styleUrls: ['./swipe-cards.component.scss'],
  animations: [
    // the fade-in/fade-out animation.
    trigger('simpleFadeAnimation', [

      state('in', style({ opacity: 1 })),
      transition(':leave',
        animate(400, style({ opacity: 0, transform: 'translateY(-200px)' })))
    ]),
    trigger('like', [
      state('in', style({ opacity: 0, transform: 'scale(1)' })),
      transition('* => like', [
        animate('550ms', keyframes([
          style({ opacity: .7, transform: 'scale(1)', offset: 0 }), // Initial state
          style({ opacity: 0, transform: 'scale(3)', offset: 1 }) // Fade out completely
        ]))
      ])
    ]),
    trigger('dislike', [
      state('in', style({ opacity: 0, transform: 'scale(1)' })),
      transition('* => dislike', [
        animate('550ms', keyframes([
          style({ opacity: .7, transform: 'scale(1)', offset: 0 }), // Initial state
          style({ opacity: 0, transform: 'scale(3)', offset: 1 }) // Fade out completely
        ]))
      ])
    ])
  ]
})
export class SwipeCardsComponent implements OnInit {
  members: Member[] = [];
  pagination: Pagination | undefined;
  userParams: UserParams | undefined;
  animationState: string = 'in';
  animationState2: string = 'in';
  constructor(private memberService: MembersService, private route: ActivatedRoute, private toastr: ToastrService) {
    this.userParams = this.memberService.getUserParams();
  }

  ngOnInit(): void {

    this.route.params.subscribe(() => {
      this.loadMembers();
      console.log("NOOOO WYSOW");
    });
  }


  loadMembers() {
    if (this.userParams) {
      this.userParams.withoutLikes = true;
      this.userParams.orderBy = 'points';
      this.userParams.pageSize = 3;
      this.memberService.getMembers(this.userParams).subscribe({
        next: response => {
          if (response.result)
            this.members.unshift(...response.result.reverse());
          console.log(this.members);
          console.log("NOOOO WYSOW3333333");
          this.pagination = response.pagination;
        }
      })
      console.log("NOOOO WYSOW222222222");
    }
  }

  removeMember(member: Member) {
    this.members = this.members.filter(object => {
      return object.userName !== member.userName;
    });
  }

  addLike(member: Member) {
    this.memberService.addLike(member.userName).subscribe({
      next: () => this.toastr.success('You have liked ' + member.knownAs)
    })
  }
  resetDislikes() {
    this.memberService.resetDislikes().subscribe({
      next: () => { 
        this.toastr.success('Dislikes reseted successfully');
        this.loadMembers();
    }
    })
  }

  likeUser(member: Member) {
    this.memberService.addLike(member.userName).subscribe({
      next: response => {
        this.removeMember(member);
        this.animationState = 'like';
        if(response.isMatch) this.toastr.success("IT'S MATCH !");
        setTimeout(() => {
          this.animationState = 'in';
        }, 550);
        this.checkEmpty();
      }
    })
  }

  dislikeUser(member: Member) {
    this.memberService.dislike(member.userName).subscribe({
      next: () => {
        this.removeMember(member);
        this.animationState2 = 'dislike';
        setTimeout(() => {
          this.animationState2 = 'in';
        }, 550);
        this.checkEmpty();
      }
    })

  }

  checkEmpty() {
    if (this.members.length === 1) this.loadMembers();
  }

}
