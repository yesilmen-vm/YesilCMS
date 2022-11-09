function TooltipExtended() {
    /**
     * Add event-listeners
     */
    this.initialize = function () {
        // Add mouse-over event listeners
        this.addEvents();
    };
    this.addEvents = function () {
        TooltipExtended.addEvents.handleMouseMove = function (e) {
            TooltipExtended.move(e);
        };

        $("[data-item]").hover(
            function (e) {
                $(document).bind('mousemove', TooltipExtended.addEvents.handleMouseMove);
                if (/^item=[0-9]*$/.test($(this).attr("data-item"))) {
                    TooltipExtended.Item.get(this, function (data) {
                        TooltipExtended.show(data);
                        TooltipExtended.move(e);
                    });
                }
            },
            function () {
                $(document).unbind('mousemove', TooltipExtended.addEvents.handleMouseMove);
                $("#tooltip").hide();

                //if (TooltipExtended.Item.currentAjax != null)
                //    TooltipExtended.Item.currentAjax.abort();
            }
        );

        $("[data-spell]").hover(
            function (e) {
                $(document).bind('mousemove', TooltipExtended.addEvents.handleMouseMove);
                if (/^spell=[0-9]*$/.test($(this).attr("data-spell"))) {
                    TooltipExtended.Spell.get(this, function (data) {
                        TooltipExtended.show(data);
                        TooltipExtended.move(e);
                    });
                }
            },
            function () {
                $(document).unbind('mousemove', TooltipExtended.addEvents.handleMouseMove);
                $("#tooltip").hide();

                //if (TooltipExtended.Spell.currentAjax != null)
                //    TooltipExtended.Spell.currentAjax.abort();
            }
        );

    };

    /**
     * Used to support Ajax content
     * Reloads the tooltip elements
     */
    this.refresh = function () {
        // Re-add
        this.addEvents();
    };

    /**
     * Displays the tooltip
     * @param data
     */
    this.show = function (data) {
        $("#tooltip").html(data).show();
    };

    /**
     * Moves tooltip
     * @param e
     */
    this.move = function (e) {
        var mousex = e.pageX;
        var mousey = e.pageY;
        var twidth = $("#tooltip").width();

        if (mousex > window.innerWidth / 1.5) {
            mousex = mousex - twidth - 25;
        }

        $("#tooltip").css("left", mousex).css("top", mousey + 25);
    };

    /**
     * Item tooltip object
     */
    this.Item = new function () {
        /**
         * Loading HTML
         */
        this.loading = "Loading item...";

        /**
         * The currently displayed item ID
         */
        this.currentId = false;

        /**
         * Used to interrupt the ajax in progress on mouse out
         */
        this.currentAjax = null;

        /**
         * Runtime cache
         */
        this.cache = {
            spells: []
        };

        /**
         * Load an item and display it in the tooltip
         * @param element
         * @param callback
         */
        this.get = function (element, callback) {
            var obj = $(element);
            var realm = obj.attr("data-realm");
            var id = obj.attr("data-item").replace("item=", "");
            var patch = obj.attr("data-patch");

            var slot = parseInt(obj.attr("data-item-slot"));

            //try getting the visit cache
            var cache = $.data(element, 'tooltip-cache');

            TooltipExtended.Item.currentId = id;

            if (typeof cache != 'undefined') {
                callback(cache)
            } else {
                callback(this.loading);

                TooltipExtended.Item.currentAjax = $.getJSON(baseURL + "/api/v1/tooltip/item/" + id + "/" + patch, function (data) {
                    icon = ($(data)[0].icon);
                    data = $(data.tooltip).prepend("<p class='t-icon' style='background-image: url(" + imgURL + icon + ".png);'><ins class='t-frame'></ins></p>");

                    //handle item player data
                    if (typeof tooltipCharData != 'undefined' && (slot in tooltipCharData)) {
                        var ItemPlayerData = tooltipCharData[slot];

                        //handle enchants
                        if (ItemPlayerData.enchant) {
                            data.find('#tooltip-item-enchantments').html(ItemPlayerData.enchant.description);
                        }
                    }

                    //handle active item set pieces
                    if (typeof tooltipEqItemArr != 'undefined') {
                        //loop trough the itemset pieces
                        data.find('.item-set-piece').each(function (i, e) {
                            var possibleEntriesString = $(e).attr('data-possible-entries');
                            //split into array
                            var possibleEntries = [];
                            //make sure we have more than 1 entry
                            if (possibleEntriesString.indexOf(':') > -1) {
                                possibleEntries = possibleEntriesString.split(':');
                            } else {
                                possibleEntries[0] = possibleEntriesString;
                            }
                            //loop the possible entries and check if one of the is equipped
                            $.each(possibleEntries, function (i2, v2) {
                                if ($.inArray(parseInt(v2), tooltipEqItemArr) > -1) {
                                    //active the piece
                                    $(e).addClass('q8');
                                    $(e).addClass('item-set-active-piece');
                                }
                            });
                        });
                        //get the active pieces count
                        var activePiecesCount = data.find('.item-set-active-piece').length;
                        //update the equipped item set pieces count
                        if (data.find('#tooltip-item-set-count').length > 0) {
                            data.find('#tooltip-item-set-count').html(activePiecesCount);
                        }
                        //update the set bonuses
                        if (activePiecesCount > 0) {
                            data.find('.item-set-bonus').each(function (i, e) {
                                var requiredPieces = $(e).attr('data-bonus-required-items');
                                //activate the set bonus
                                if (activePiecesCount >= requiredPieces) {
                                    $(e).addClass('q2');
                                }
                            });
                        }
                    }

                    // Cache it this visit
                    $.data(element, 'tooltip-cache', data);

                    // Make sure it's still visible
                    if ($("#tooltip").is(":visible") && TooltipExtended.Item.currentId == id) {
                        callback(data);
                    }
                }).fail(function (jqXHR, status, error) {
                    if (status == 'parseerror') {
                        error_text = "Item request is not valid.";
                        $("#tooltip").html(error_text).show();
                        ErrorCounter++;

                    } else {
                        error_text = "<p class='t-icon' style='background-image: url(" + imgURL + "INV_Misc_QuestionMark_2.png);'><ins class='t-frame'></ins></p><strong>Item not found.</strong><br/>Item does not exists in the database or not implemented in selected Patch.";
                        $("#tooltip").html(error_text).show();
                        ErrorCounter++;
                    }
                    if (ErrorCounter > 2) { //Try 3 times before caching the error for each element.
                        $.data(element, 'tooltip-cache', error_text);
                        ErrorCounter = 0;
                    }
                });
            }
        }
    }

    /**
     * Spell tooltip object
     */
    this.Spell = new function () {
        /**
         * Loading HTML
         */
        this.loading = "Loading spell...";

        /**
         * The currently displayed spell ID
         */
        this.currentId = false;

        /**
         * Used to interrupt the ajax in progress on mouse out
         */
        this.currentAjax = null;

        /**
         * Runtime cache
         */
        this.cache = {};

        /**
         * Load an spell and display it in the tooltip
         * @param element
         * @param callback
         */
        this.get = function (element, callback) {
            var obj = $(element);
            var realm = obj.attr("data-realm");
            var id = obj.attr("data-spell").replace("spell=", "");
            var patch = obj.attr("data-patch");

            //try getting the visit cache
            var cache = $.data(element, 'tooltip-cache');

            TooltipExtended.Spell.currentId = id;

            if (typeof cache != 'undefined') {
                callback(cache)
            } else {
                callback(this.loading);

                TooltipExtended.Spell.currentAjax = $.getJSON(baseURL + "/api/v1/tooltip/spell/" + id + "/" + patch, function (data) {
                    icon = ($(data)[0].icon);
                    data = $(data.tooltip).prepend("<p class='t-icon' style='background-image: url(" + imgURL + icon + ".png);'><ins class='t-frame'></ins></p>");

                    // Cache it this visit
                    $.data(element, 'tooltip-cache', data);

                    // Make sure it's still visible
                    if ($("#tooltip").is(":visible") && TooltipExtended.Spell.currentId == id) {
                        callback(data);
                    }
                }).fail(function (jqXHR, status, error) {
                    let error_text;
                    if (status == 'parseerror') {
                        error_text = "Spell request is not valid.";
                        $("#tooltip").html(error_text).show();
                        ErrorCounter++;

                    } else {
                        error_text = "<p class='t-icon' style='background-image: url(" + imgURL + "INV_Misc_QuestionMark_2.png);'><ins class='t-frame'></ins></p><strong>Spell not found.</strong><br/>Spell does not exists in the database or not implemented in selected Patch.";
                        $("#tooltip").html(error_text).show();
                        ErrorCounter++;
                    }
                    if (ErrorCounter > 2) { //Try 3 times before caching the error for each element.
                        $.data(element, 'tooltip-cache', error_text);
                        ErrorCounter = 0;
                    }
                });
            }
        }
    }
}

var ErrorCounter = 0;
var TooltipExtended = new TooltipExtended();

//initialize the extended tooltip
TooltipExtended.initialize();
