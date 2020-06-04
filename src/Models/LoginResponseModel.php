<?php


namespace TikTokAPI\Models;



use EJM\MainMapper;

/**
 * @method getAppId()
 * @method getUserId()
 * @method getUserIdStr()
 * @method getName()
 * @method getScreenName()
 * @method getAvatarUrl()
 * @method getUserVerified()
 * @method getVerifiedContent()
 * @method getVerifiedAgency()
 * @method getIsBlocked()
 * @method getIsBlocking()
 * @method getBgImgUrl()
 * @method getGender()
 * @method getMediaId()
 * @method getUserAuthInfo()
 * @method getIndustry()
 * @method getArea()
 * @method getCanBeFoundByPhone()
 * @method getMobile()
 * @method getBirthday()
 * @method getDescription()
 * @method getEmail()
 * @method getNewUser()
 * @method getSessionKey()
 * @method getIsRecommendAllowed()
 * @method getRecommendHintMessage()
 * @method getFollowingsCount()
 * @method getFollowersCount()
 * @method getVisitCountRecent()
 * @method getSkipEditProfile()
 * @method getIsManualSetUserInfo()
 * @method getDeviceId()
 * @method getCountryCode()
 * @method getHasPassword()
 * @method getShareToRepost()
 * @method getUserDecoration()
 * @method getUserPrivacyExtend()
 * @method getOldUserId()
 * @method getOldUserIdStr()
 * @method getSecUserId()
 * @method getSecOldUserId()
 * @method getVcdAccount()
 * @method getVcdRelation()
 * @method getCanBindVisitorAccount()
 * @method getIsVisitorAccount()
 * @method getIsOnlyBindIns()
 * @method getCaptcha()
 * @method getErrorCode()
 */

class LoginResponseModel extends MainMapper {
    const MAP =
        [
            'app_id' => 'string',
            'user_id' => 'string',
            'user_id_str' => 'string',
            'name' => 'string',
            'screen_name' => 'string',
            'avatar_url' => 'string',
            'user_verified' => 'string',
            'verified_content' => 'string',
            'verified_agency' => 'string',
            'is_blocked' => 'string',
            'is_blocking' => 'string',
            'bg_img_url' => 'string',
            'gender' => 'string',
            'media_id' => 'string',
            'user_auth_info' => 'string',
            'industry' => 'string',
            'area' => 'string',
            'can_be_found_by_phone' => 'string',
            'mobile' => 'string',
            'birthday' => 'string',
            'description' => 'string',
            'email' => 'string',
            'new_user' => 'string',
            'session_key' => 'string',
            'is_recommend_allowed' => 'string',
            'recommend_hint_message' => 'string',
            'connects' => 'Connects',
            'followings_count' => 'string',
            'followers_count' => 'string',
            'visit_count_recent' => 'string',
            'skip_edit_profile' => 'string',
            'is_manual_set_user_info' => 'string',
            'device_id' => 'string',
            'country_code' => 'string',
            'has_password' => 'string',
            'share_to_repost' => 'string',
            'user_decoration' => 'string',
            'user_privacy_extend' => 'string',
            'old_user_id' => 'string',
            'old_user_id_str' => 'string',
            'sec_user_id' => 'string',
            'sec_old_user_id' => 'string',
            'vcd_account' => 'string',
            'vcd_relation' => 'string',
            'can_bind_visitor_account' => 'string',
            'is_visitor_account' => 'string',
            'is_only_bind_ins' => 'string',
            'captcha' => 'string',
            'error_code' => 'string'
        ];
}

